CREATE TABLE `codificar`.`tb_deputados`  (
	`id_deputado` int(11) NOT NULL,
	`no_deputado` text,
	`ds_partido` VARCHAR(50),
	`nu_tag_localizacao` INT(255),
  PRIMARY KEY (`id_deputado`)
);

CREATE TABLE `codificar`.`tb_verbas`  (
	`id_verba` int(11) NOT NULL AUTO_INCREMENT,
	`id_deputado` int(11),
	`dt_referencia` date,
	`dt_mes_referencia` VARCHAR(4),
	`dt_ano_referencia` VARCHAR(10),
	`nu_total_verbas` INT(255),
  PRIMARY KEY (`id_verba`)
);

CREATE TABLE `codificar`.`tb_verbas_mes`  (
	`id_verba_mes` INT(11) NOT NULL AUTO_INCREMENT,
	`id_deputado` INT(11),
	`dt_referencia` date,
	`dt_mes_referencia` VARCHAR(4),
	`dt_ano_referencia` VARCHAR(10),
	`id_tipo_despesa` INT(11),
	`ds_tipo_despesa` VARCHAR(255),
	`nu_valor` INT(255),
  PRIMARY KEY (`id_verba_mes`)
);

CREATE TABLE `codificar`.`tb_verbas_detalhes`  (
	`id_verba_detalhe` int(11) NOT NULL AUTO_INCREMENT,
	`id_deputado` int(11),
	`dt_referencia` date,
	`dt_mes_referencia` VARCHAR(4),
	`dt_ano_referencia` VARCHAR(10),
	`nu_valor_reembolso` FLOAT(255,0),
	`dt_emissao` DATE,
	`nu_cpf_cnpj` INT(50),
	`nu_valor_despesa` FLOAT(255,0),
	`no_emitente` text,
	`ds_documento` VARCHAR(255),
	`id_tipo_despesa` int(11),
	`ds_tipo_despesa` VARCHAR(255),
  PRIMARY KEY (`id_verba_detalhe`)
);

CREATE TABLE `codificar`.`tb_redes_sociais`  (
  `id_rede_social` int(11),
  `id_deputado` int(11),
  `no_rede_social` text,
  `ds_url` varchar(255)
);