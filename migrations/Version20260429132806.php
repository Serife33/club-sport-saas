<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260429132806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, sport VARCHAR(100) NOT NULL, slug VARCHAR(160) NOT NULL, contact_email VARCHAR(180) NOT NULL, phone VARCHAR(20) DEFAULT NULL, logo_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, evaluation_date DATE NOT NULL, season VARCHAR(15) NOT NULL, criteria JSON NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, evaluator_id INT NOT NULL, player_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_1323A57543575BE2 (evaluator_id), INDEX IDX_1323A57599E6F5DF (player_id), INDEX IDX_1323A575296CD8AE (team_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(180) NOT NULL, description LONGTEXT DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, recurrence_rule VARCHAR(255) DEFAULT NULL, season_start_date DATE NOT NULL, season_end_date DATE NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, team_id INT NOT NULL, event_type_id INT NOT NULL, creator_id INT NOT NULL, INDEX IDX_3BAE0AA7296CD8AE (team_id), INDEX IDX_3BAE0AA7401B253C (event_type_id), INDEX IDX_3BAE0AA761220EA6 (creator_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE event_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, color VARCHAR(15) DEFAULT NULL, is_default VARCHAR(3) NOT NULL, status VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, club_id INT DEFAULT NULL, INDEX IDX_93151B8261190A32 (club_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, message LONGTEXT NOT NULL, read_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, feedback_type_id INT NOT NULL, player_id INT NOT NULL, coach_id INT NOT NULL, occurrence_id INT DEFAULT NULL, INDEX IDX_D22944585415AC30 (feedback_type_id), INDEX IDX_D229445899E6F5DF (player_id), INDEX IDX_D22944583C105691 (coach_id), INDEX IDX_D229445830572FAC (occurrence_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE feedback_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, nature VARCHAR(20) NOT NULL, severity VARCHAR(15) DEFAULT NULL, is_default VARCHAR(3) NOT NULL, status VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, club_id INT DEFAULT NULL, INDEX IDX_497CB6A061190A32 (club_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE match_stat (id INT AUTO_INCREMENT NOT NULL, minutes_played INT DEFAULT NULL, goals INT DEFAULT NULL, assists INT DEFAULT NULL, yellow_cards INT DEFAULT NULL, white_cards INT DEFAULT NULL, red_card VARCHAR(3) DEFAULT NULL, coach_rating INT DEFAULT NULL, participation_id INT NOT NULL, position_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_41D74E536ACE3B73 (participation_id), INDEX IDX_41D74E53DD842E46 (position_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE occurrence (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, location VARCHAR(255) DEFAULT NULL, kind VARCHAR(15) NOT NULL, cancellation_reason VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, event_id INT NOT NULL, INDEX IDX_BEFD81F371F7E88B (event_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(15) NOT NULL, actual_presence VARCHAR(15) DEFAULT NULL, absence_reason VARCHAR(20) DEFAULT NULL, document_url VARCHAR(255) DEFAULT NULL, document_type VARCHAR(30) DEFAULT NULL, declared_at DATETIME DEFAULT NULL, notified_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, occurrence_id INT NOT NULL, user_id INT NOT NULL, declared_by_id INT DEFAULT NULL, INDEX IDX_AB55E24F30572FAC (occurrence_id), INDEX IDX_AB55E24FA76ED395 (user_id), INDEX IDX_AB55E24FC48B85B0 (declared_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE position (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, is_default VARCHAR(3) NOT NULL, status VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, club_id INT DEFAULT NULL, INDEX IDX_462CE4F561190A32 (club_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, season VARCHAR(15) NOT NULL, status VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(80) NOT NULL, lastname VARCHAR(80) NOT NULL, birhdate DATE NOT NULL, phone VARCHAR(20) DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, role VARCHAR(15) NOT NULL, status VARCHAR(15) NOT NULL, emergency_contact_name VARCHAR(100) DEFAULT NULL, emergency_contact_phone VARCHAR(20) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, club_id INT DEFAULT NULL, team_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, INDEX IDX_8D93D64961190A32 (club_id), INDEX IDX_8D93D649296CD8AE (team_id), INDEX IDX_8D93D649727ACA70 (parent_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57543575BE2 FOREIGN KEY (evaluator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57599E6F5DF FOREIGN KEY (player_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7401B253C FOREIGN KEY (event_type_id) REFERENCES event_type (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA761220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_type ADD CONSTRAINT FK_93151B8261190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944585415AC30 FOREIGN KEY (feedback_type_id) REFERENCES feedback_type (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D229445899E6F5DF FOREIGN KEY (player_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944583C105691 FOREIGN KEY (coach_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D229445830572FAC FOREIGN KEY (occurrence_id) REFERENCES occurrence (id)');
        $this->addSql('ALTER TABLE feedback_type ADD CONSTRAINT FK_497CB6A061190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE match_stat ADD CONSTRAINT FK_41D74E536ACE3B73 FOREIGN KEY (participation_id) REFERENCES participation (id)');
        $this->addSql('ALTER TABLE match_stat ADD CONSTRAINT FK_41D74E53DD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('ALTER TABLE occurrence ADD CONSTRAINT FK_BEFD81F371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F30572FAC FOREIGN KEY (occurrence_id) REFERENCES occurrence (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FC48B85B0 FOREIGN KEY (declared_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F561190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64961190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649727ACA70 FOREIGN KEY (parent_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57543575BE2');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57599E6F5DF');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575296CD8AE');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7296CD8AE');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7401B253C');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA761220EA6');
        $this->addSql('ALTER TABLE event_type DROP FOREIGN KEY FK_93151B8261190A32');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944585415AC30');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D229445899E6F5DF');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944583C105691');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D229445830572FAC');
        $this->addSql('ALTER TABLE feedback_type DROP FOREIGN KEY FK_497CB6A061190A32');
        $this->addSql('ALTER TABLE match_stat DROP FOREIGN KEY FK_41D74E536ACE3B73');
        $this->addSql('ALTER TABLE match_stat DROP FOREIGN KEY FK_41D74E53DD842E46');
        $this->addSql('ALTER TABLE occurrence DROP FOREIGN KEY FK_BEFD81F371F7E88B');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F30572FAC');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FA76ED395');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FC48B85B0');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F561190A32');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64961190A32');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649296CD8AE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649727ACA70');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE feedback_type');
        $this->addSql('DROP TABLE match_stat');
        $this->addSql('DROP TABLE occurrence');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
