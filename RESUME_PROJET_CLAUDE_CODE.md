# Résumé du projet — SaaS Club de Sport
## Document de passation pour Claude Code

---

## 1. Contexte du projet

SaaS de gestion de club de sport multi-catégories (U6 → Senior).
Le cœur du projet est le **suivi des présences et absences** des joueurs.

---

## 2. Stack technique

- **Framework** : Symfony 7.4 / PHP 8.4
- **ORM** : Doctrine ORM
- **Base de données** : MySQL 8
- **Auth** : LoginFormAuthenticator (make:auth)
- **Emails** : Symfony Mailer + Twig templates
- **Queue** : Symfony Messenger
- **Frontend** : Twig + Tailwind CSS
- **Environnement** : Docker (PHP 8.4, MySQL 8, Nginx, phpMyAdmin, Mailpit)

---

## 3. Infrastructure Docker

**Fichier `compose.yaml` à la racine :**

```yaml
services:
  php:
    build: ./docker/php
    volumes:
      - .:/var/www/html
    depends_on:
      - mysql

  nginx:
    image: nginx:alpine
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php

  mysql:
    image: mysql:8.0
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: club_sport
    volumes:
      - mysql_data:/var/lib/mysql

  mailer:
    image: axllent/mailpit
    ports:
      - "1025"
      - "8025"
    environment:
      MP_SMTP_AUTH_ACCEPT_ANY: 1
      MP_SMTP_AUTH_ALLOW_INSECURE: 1

  phpmyadmin:
    image: phpmyadmin:latest
    ports:
      - "8081:80"
    environment:
      PMA_HOST: mysql
      PMA_USER: root
      PMA_PASSWORD: root
    depends_on:
      - mysql

volumes:
  mysql_data:
```

**URLs locales :**
- http://localhost:8080 → Symfony
- http://localhost:8081 → phpMyAdmin
- http://localhost:8025 → Mailpit (emails de test)

**Commandes Docker quotidiennes :**
```bash
docker compose up -d          # démarrer
docker compose down           # arrêter
docker compose exec php bash  # entrer dans le conteneur PHP
```

---

## 4. Base de données

**14 tables créées via migrations Doctrine :**

| Table | Rôle |
|---|---|
| `club` | Tenant principal du SaaS |
| `user` | Tous les acteurs (admin, coach, joueur, parent) |
| `team` | Équipes du club (U6, U17, U18, Seniors…) |
| `event_type` | Types d'événements configurables par club |
| `event` | Événement récurrent parent (règle iCal) |
| `occurrence` | Instance concrète d'un event à une date précise |
| `participation` | ⭐ Table centrale — présence/absence d'un joueur |
| `invitation` | Gestion des invitations par email |
| `evaluation` | Évaluation individuelle coach → joueur |
| `match_stat` | Stats d'un joueur par match |
| `position` | Postes configurables par club |
| `feedback_type` | Types de feedback (avertissement/félicitation) |
| `feedback` | Avertissement ou félicitation coach → joueur |
| `doctrine_migration_versions` | Table interne Doctrine |

---

## 5. Entités & relations clés

### `User`
```
- id, email, password (auth Symfony)
- firstname, lastname, birthdate, phone, photoUrl
- role (ADMIN, COACH, PLAYER, PARENT)
- status (ACTIVE, INACTIVE, SUSPENDED)
- emergencyContactName, emergencyContactPhone
- Relations : club (ManyToOne), team (ManyToOne), parent (ManyToOne self-ref)
- Implements UserInterface, PasswordAuthenticatedUserInterface
- Trait : TimestampableEntity
```

**Règle métier critique :**
- `birthdate < today - 14 ans` → le joueur déclare lui-même ses absences
- `birthdate > today - 14 ans` → c'est le parent qui déclare

### `Club`
```
- id, name, sport, slug (unique), contact_email, phone, logo_url
- Trait : TimestampableEntity
```

### `Event` (patron récurrent)
```
- id, title, description, location
- recurrenceRule (format iCal ex: FREQ=WEEKLY;BYDAY=MO)
- seasonStartDate, seasonEndDate, startTime, endTime
- Relations : team (ManyToOne), eventType (ManyToOne), creator (ManyToOne → User)
- Trait : TimestampableEntity
```

### `Occurrence` (instance concrète)
```
- id, date, startTime, endTime, location
- kind (NORMAL, MODIFIED, CANCELLED)
- cancellationReason
- Relations : event (ManyToOne)
- Trait : TimestampableEntity
```

**Règle métier critique :**
- `NORMAL` → générée automatiquement depuis la recurrenceRule
- `MODIFIED` → modifiée ponctuellement pour cette date uniquement
- `CANCELLED` → annulée, email envoyé à tous les convoqués

### `Participation` ⭐ Table centrale
```
- id, status, absenceReason, documentUrl, documentType
- actualPresence, declaredAt, notifiedAt
- Relations :
  - occurrence (ManyToOne, NOT NULL)
  - user (ManyToOne, NOT NULL) → le joueur
  - declaredBy (ManyToOne, nullable) → le parent qui déclare
- Trait : TimestampableEntity
```

**Valeurs de `status` :**
`PENDING, PRESENT, ABSENT, LATE, EXCUSED`

**Valeurs de `absenceReason` :**
`SICKNESS, INJURY, WORK, VACATION, PERSONAL, OTHER`

**Valeurs de `actualPresence` :**
`PRESENT, ABSENT, LATE` (renseigné par le coach lors de l'appel)

**Règle métier critique :**
- `status` = ce que le joueur déclare en avance
- `actualPresence` = ce que le coach constate le jour J
- Les stats se calculent toujours sur `actualPresence`, jamais sur `status`
- `absenceReason` obligatoire si `status = ABSENT` ou `EXCUSED`
- `notifiedAt` = date d'envoi du mail automatique (évite les doublons)

### `Invitation`
```
- id, email, token, role, sentAt, expiresAt, usedAt
- Relations :
  - team (ManyToOne, nullable)
  - invitedBy (ManyToOne → User)
- Trait : TimestampableEntity
```

---

## 6. Authentification

**Fichiers générés :**
- `src/Security/LoginFormAuthenticator.php`
- `src/Controller/SecurityController.php`
- `templates/security/login.html.twig`

**Après connexion réussie → redirige vers `app_dashboard`**

**`config/packages/security.yaml` :**
```yaml
security:
    password_hashers:
        App\Entity\User:
            algorithm: auto

    providers:
        app_user_provider:
            entity:
                class: App\Entity\User
                property: email

    firewalls:
        dev:
            pattern: ^/(_profiler|_wdt|assets|build)/
            security: false
        main:
            lazy: true
            provider: app_user_provider

    access_control:
        - { path: ^/login, roles: PUBLIC_ACCESS }
        - { path: ^/, roles: IS_AUTHENTICATED_FULLY }
```

---

## 7. Bundles installés

| Bundle | Rôle |
|---|---|
| `symfony/webapp` | Meta-pack Symfony complet |
| `stof/doctrine-extensions-bundle` | Timestampable (created_at, updated_at auto) |
| `axllent/mailpit` | Serveur mail de test (Docker) |

**Config `stof_doctrine_extensions.yaml` :**
```yaml
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true
```

---

## 8. Ce qui est fait

- ✅ Docker configuré et fonctionnel
- ✅ Symfony 7.4 installé
- ✅ 12 entités créées avec toutes leurs relations
- ✅ Migrations exécutées — 14 tables en base
- ✅ Authentification configurée (login/logout)
- ✅ DashboardController créé (route `app_dashboard`)
- ✅ UserController créé avec méthode `index()` en cours

---

## 9. Ce qui reste à faire (MVP)

### Priorité 1 — Gestion des membres
- [ ] Terminer `UserController` (index, show, edit)
- [ ] Créer `InvitationController` (envoi invitation + activation compte)
- [ ] Formulaire `UserType`
- [ ] Templates Twig user

### Priorité 2 — Événements & occurrences
- [ ] `make:crud Event`
- [ ] `OccurrenceGenerator` service (parse recurrenceRule iCal → génère les occurrences)
- [ ] Controller gestion occurrences (modifier/annuler une date précise)

### Priorité 3 — Appel & présences (cœur du MVP)
- [ ] `AppelController` — le coach fait l'appel
- [ ] `ParticipationController` — le joueur/parent déclare une absence
- [ ] `AbsenceNotificationMessage` + `AbsenceNotificationHandler` (Messenger)
- [ ] Email automatique aux absents (joueur ou parent selon âge)
- [ ] Email annulation occurrence

### Priorité 4 — Sécurité
- [ ] `UserVoter` — qui peut voir/modifier un profil
- [ ] `OccurrenceVoter` — qui peut créer/modifier/annuler
- [ ] `ParticipationVoter` — qui peut faire l'appel, déclarer une absence

---

## 10. Flux métier principal — L'appel

```
Coach ouvre la liste des convoqués pour une occurrence
        ↓
Il coche PRESENT / ABSENT / LATE pour chaque joueur
(actualPresence sur Participation)
        ↓
Il valide l'appel
        ↓
Symfony Messenger dispatche AbsenceNotificationMessage
pour chaque joueur marqué ABSENT sans justificatif
        ↓
AbsenceNotificationHandler envoie l'email :
  - joueur >= 14 ans → email au joueur
  - joueur < 14 ans  → email au parent (user.parent)
        ↓
participation.notifiedAt = now()
```

---

## 11. Calcul des statistiques de présence

```sql
SELECT
    et.name AS event_type,
    COUNT(*) AS total,
    SUM(CASE WHEN p.actual_presence IN ('PRESENT','LATE') THEN 1 ELSE 0 END) AS presents,
    ROUND(
        SUM(CASE WHEN p.actual_presence IN ('PRESENT','LATE') THEN 1 ELSE 0 END)
        * 100.0 / NULLIF(COUNT(*), 0), 1
    ) AS taux_presence
FROM participation p
JOIN occurrence o ON o.id = p.occurrence_id
JOIN event e ON e.id = o.event_id
JOIN event_type et ON et.id = e.event_type_id
WHERE p.user_id = :joueur_id
  AND o.kind != 'CANCELLED'
GROUP BY et.name;
```

**Important :** les stats se calculent sur `actual_presence` (réalité coach), jamais sur `status` (déclaration joueur).

---

## 12. Structure du projet

```
club-sport-saas/
├── docker/
│   ├── php/Dockerfile
│   └── nginx/default.conf
├── compose.yaml
├── src/
│   ├── Controller/
│   │   ├── DashboardController.php ✅
│   │   ├── SecurityController.php  ✅
│   │   └── UserController.php      🚧 en cours
│   ├── Entity/
│   │   ├── Club.php          ✅
│   │   ├── User.php          ✅
│   │   ├── Team.php          ✅
│   │   ├── EventType.php     ✅
│   │   ├── Event.php         ✅
│   │   ├── Occurrence.php    ✅
│   │   ├── Participation.php ✅
│   │   ├── Invitation.php    ✅
│   │   ├── Evaluation.php    ✅
│   │   ├── MatchStat.php     ✅
│   │   ├── Position.php      ✅
│   │   ├── FeedbackType.php  ✅
│   │   └── Feedback.php      ✅
│   ├── Repository/           ✅ (généré automatiquement)
│   └── Security/
│       └── LoginFormAuthenticator.php ✅
├── templates/
│   ├── dashboard/index.html.twig ✅
│   ├── security/login.html.twig  ✅
│   └── user/index.html.twig      🚧 à créer
├── config/
│   └── packages/
│       ├── security.yaml              ✅
│       └── stof_doctrine_extensions.yaml ✅
└── migrations/               ✅ 2 migrations exécutées
```
