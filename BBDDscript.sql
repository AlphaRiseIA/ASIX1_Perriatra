-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema db_perriatra
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema db_perriatra
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_perriatra` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `db_perriatra` ;

-- -----------------------------------------------------
-- Table `db_perriatra`.`especialidades`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_perriatra`.`especialidades` (
  `id_e` INT NOT NULL AUTO_INCREMENT,
  `Nombre_e` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id_e`),
  UNIQUE INDEX `Nombre_e` (`Nombre_e` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 67
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `db_perriatra`.`especie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_perriatra`.`especie` (
  `id_esp` INT NOT NULL AUTO_INCREMENT,
  `nombre_esp` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id_esp`),
  UNIQUE INDEX `Nombre_e` (`nombre_esp` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `db_perriatra`.`propietario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_perriatra`.`propietario` (
  `DNI_p` INT NOT NULL,
  `Nombre_p` VARCHAR(55) NOT NULL,
  `Direccion_p` TEXT NULL DEFAULT NULL,
  `Telf_p` INT NOT NULL,
  `Mail_p` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`DNI_p`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `db_perriatra`.`raza`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_perriatra`.`raza` (
  `id_r` INT NOT NULL AUTO_INCREMENT,
  `Nombre_r` VARCHAR(50) NOT NULL,
  `id_esp` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id_r`),
  INDEX `id_e` (`id_esp` ASC) VISIBLE,
  CONSTRAINT `fk_Raza_Especie`
    FOREIGN KEY (`id_esp`)
    REFERENCES `db_perriatra`.`especie` (`id_esp`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 28
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `db_perriatra`.`veterinarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_perriatra`.`veterinarios` (
  `id_v` INT NOT NULL AUTO_INCREMENT,
  `Nombre_v` VARCHAR(50) NULL DEFAULT NULL,
  `Telf_v` VARCHAR(15) NULL DEFAULT NULL,
  `id_e` INT NULL DEFAULT NULL,
  `Fecha_Contrato_v` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Salario_v` DECIMAL(10,2) NULL DEFAULT NULL,
  PRIMARY KEY (`id_v`),
  INDEX `id_e` (`id_e` ASC) VISIBLE,
  CONSTRAINT `fk_Veterinario_Especialidad`
    FOREIGN KEY (`id_e`)
    REFERENCES `db_perriatra`.`especialidades` (`id_e`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `db_perriatra`.`mascota`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_perriatra`.`mascota` (
  `Chip_m` INT NOT NULL,
  `Nombre_m` VARCHAR(50) NOT NULL,
  `genero_m` VARCHAR(1) NULL DEFAULT NULL,
  `id_r` INT NOT NULL,
  `Fecha_Nacimiento_m` DATE NULL DEFAULT NULL,
  `DNI_p` INT NOT NULL,
  `id_v` INT NOT NULL,
  PRIMARY KEY (`Chip_m`),
  INDEX `mascota_raza_fk` (`id_r` ASC) VISIBLE,
  INDEX `mascota_propietario_fk` (`DNI_p` ASC) VISIBLE,
  INDEX `mascota_vet_fk` (`id_v` ASC) VISIBLE,
  CONSTRAINT `fk_Mascota_Propietario`
    FOREIGN KEY (`DNI_p`)
    REFERENCES `db_perriatra`.`propietario` (`DNI_p`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Mascota_Raza`
    FOREIGN KEY (`id_r`)
    REFERENCES `db_perriatra`.`raza` (`id_r`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Mascota_Veterinario`
    FOREIGN KEY (`id_v`)
    REFERENCES `db_perriatra`.`veterinarios` (`id_v`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `db_perriatra`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_perriatra`.`usuario` (
  `id_u` INT NOT NULL,
  `nombre_u` VARCHAR(100) NOT NULL,
  `password_u` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_u`),
  CONSTRAINT `usuario_ibfk_1`
    FOREIGN KEY (`id_u`)
    REFERENCES `db_perriatra`.`veterinarios` (`id_v`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

USE `db_perriatra`;

DELIMITER $$
USE `db_perriatra`$$
CREATE
DEFINER=`root`@`localhost`
TRIGGER `db_perriatra`.`trg_especialidades_after_delete`
AFTER DELETE ON `db_perriatra`.`especialidades`
FOR EACH ROW
BEGIN
  UPDATE `db_perriatra`.`veterinarios`
  SET id_e = NULL
  WHERE id_e = OLD.id_e;
END$$

USE `db_perriatra`$$
CREATE
DEFINER=`root`@`localhost`
TRIGGER `db_perriatra`.`trg_after_usuario_delete`
AFTER DELETE ON `db_perriatra`.`usuario`
FOR EACH ROW
BEGIN
  -- Borra el veterinario cuyo id_usuario coincida con el usuario eliminado
  DELETE FROM veterinarios
    WHERE id_v = OLD.id_u; END$$


DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
