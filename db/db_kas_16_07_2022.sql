/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.1.37-MariaDB : Database - kas
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `bank` */

DROP TABLE IF EXISTS `bank`;

CREATE TABLE `bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(25) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `pos` int(11) DEFAULT NULL,
  `norek` varchar(20) DEFAULT NULL,
  `pemilik` varchar(100) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `kodeform` varchar(10) DEFAULT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `hapus` int(11) DEFAULT '0',
  `dt` datetime DEFAULT NULL,
  `it` datetime DEFAULT NULL,
  `et` datetime DEFAULT NULL,
  `search` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pos` (`pos`),
  CONSTRAINT `bank_ibfk_1` FOREIGN KEY (`pos`) REFERENCES `perkiraan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

/*Data for the table `bank` */

insert  into `bank`(`id`,`kode`,`nama`,`pos`,`norek`,`pemilik`,`alamat`,`telp`,`kodeform`,`userid`,`hapus`,`dt`,`it`,`et`,`search`) values (57,'a00001','akbar',6,'11111','semarang','semarang','024',NULL,1,0,'2022-07-12 14:54:40','2022-07-12 14:40:21','2022-07-12 14:50:01','a00001 || akbar'),(58,'a00002','akbar',1381,'1111','akbar','semarang','024',NULL,1,0,NULL,'2022-07-12 14:40:39',NULL,'a00002 || akbar'),(59,'a00003','akbar',608,'3333','akbar','semarang','024',NULL,1,0,NULL,'2022-07-15 16:47:07','2022-07-15 16:47:19','a00003 || akbar'),(60,'a00004','agus',609,'515151','agus','semarang','024',NULL,4,0,NULL,'2022-07-16 19:27:47',NULL,'a00004 || agus');

/*Table structure for table `cabang` */

DROP TABLE IF EXISTS `cabang`;

CREATE TABLE `cabang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `va` varchar(16) DEFAULT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `kota` varchar(200) DEFAULT NULL,
  `provinsi` varchar(200) DEFAULT NULL,
  `telp` varchar(50) DEFAULT NULL,
  `contactperson` varchar(100) DEFAULT NULL,
  `foto` varchar(200) DEFAULT NULL,
  `it` datetime DEFAULT NULL,
  `et` datetime DEFAULT NULL,
  `dt` datetime DEFAULT NULL,
  `hapus` int(11) DEFAULT '0',
  `userid` int(11) DEFAULT NULL,
  `toko` varchar(100) DEFAULT NULL,
  `alamat_toko` text,
  `kota_toko` text,
  `provinsi_toko` text,
  `telp_toko` text,
  `rental` varchar(100) DEFAULT NULL,
  `alamat_rental` text,
  `kota_rental` text,
  `provinsi_rental` text,
  `safari` varchar(100) DEFAULT NULL,
  `alamat_safari` text,
  `kota_safari` text,
  `provinsi_safari` text,
  `onroad` varchar(100) DEFAULT NULL,
  `alamat_onroad` text,
  `kota_onroad` text,
  `provinsi_onroad` text,
  `offroad` varchar(100) DEFAULT NULL,
  `alamat_offroad` text,
  `kota_offroad` text,
  `provinsi_offroad` text,
  `agen1` varchar(16) DEFAULT NULL,
  `agen2` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `va` (`va`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `cabang` */

insert  into `cabang`(`id`,`va`,`nama`,`alamat`,`kota`,`provinsi`,`telp`,`contactperson`,`foto`,`it`,`et`,`dt`,`hapus`,`userid`,`toko`,`alamat_toko`,`kota_toko`,`provinsi_toko`,`telp_toko`,`rental`,`alamat_rental`,`kota_rental`,`provinsi_rental`,`safari`,`alamat_safari`,`kota_safari`,`provinsi_safari`,`onroad`,`alamat_onroad`,`kota_onroad`,`provinsi_onroad`,`offroad`,`alamat_offroad`,`kota_offroad`,`provinsi_offroad`,`agen1`,`agen2`) values (1,NULL,'Jakarta','DEWI SARTIKA RAYA ','Jakarta','Jawa Tengah','024','Kepala Sekolah',NULL,NULL,'2021-08-24 13:32:51',NULL,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,NULL,'Semarang','Semarang','Semarang','Jawa Tengah','024','',NULL,'2021-11-03 13:21:48','2021-11-03 13:27:52',NULL,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `cetakfaktur` */

DROP TABLE IF EXISTS `cetakfaktur`;

CREATE TABLE `cetakfaktur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idh` int(11) DEFAULT '0',
  `it` datetime DEFAULT NULL,
  `exp` datetime DEFAULT NULL,
  `token` varchar(100) NOT NULL,
  `jenis` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

/*Data for the table `cetakfaktur` */

insert  into `cetakfaktur`(`id`,`idh`,`it`,`exp`,`token`,`jenis`,`status`) values (1,1,'2022-07-13 16:06:17','2022-07-14 16:06:17','16775487','RECEIPT KAS KELUAR',1),(2,1,'2022-07-13 16:14:35','2022-07-14 16:14:35','35182994','RECEIPT KAS KELUAR',1),(3,1,'2022-07-13 16:15:10','2022-07-14 16:15:10','10320004','RECEIPT KAS KELUAR',1),(4,1,'2022-07-13 16:15:57','2022-07-14 16:15:57','57661712','RECEIPT KAS KELUAR',1),(5,1,'2022-07-13 16:17:39','2022-07-14 16:17:39','38720492','RECEIPT KAS KELUAR',1),(6,1,'2022-07-13 16:18:51','2022-07-14 16:18:51','50634605','RECEIPT KAS KELUAR',1),(7,1,'2022-07-13 16:19:49','2022-07-14 16:19:49','48859935','RECEIPT KAS KELUAR',1),(8,1,'2022-07-13 16:33:00','2022-07-14 16:33:00','00581219','RECEIPT KAS KELUAR',1),(9,1,'2022-07-14 15:29:30','2022-07-15 15:29:30','30310048','RECEIPT KAS KELUAR',1),(10,1,'2022-07-14 15:32:35','2022-07-15 15:32:35','34899606','RECEIPT KAS KELUAR',1),(11,1,'2022-07-14 15:56:50','2022-07-15 15:56:50','50322851','RECEIPT KAS MASUK',1),(12,1,'2022-07-14 15:57:21','2022-07-15 15:57:21','21583639','RECEIPT KAS MASUK',1),(13,1,'2022-07-14 15:57:53','2022-07-15 15:57:53','53023437','RECEIPT KAS MASUK',1),(14,1,'2022-07-14 15:58:13','2022-07-15 15:58:13','13464606','RECEIPT KAS MASUK',1),(15,1,'2022-07-14 15:59:32','2022-07-15 15:59:32','32714139','RECEIPT KAS MASUK',1),(16,2,'2022-07-14 15:59:54','2022-07-15 15:59:54','54555389','RECEIPT KAS MASUK',1),(17,2,'2022-07-14 16:00:10','2022-07-15 16:00:10','10191283','RECEIPT KAS MASUK',1),(18,2,'2022-07-14 16:00:24','2022-07-15 16:00:24','24219085','RECEIPT KAS MASUK',1),(19,2,'2022-07-14 16:00:47','2022-07-15 16:00:47','47302405','RECEIPT KAS MASUK',1),(20,2,'2022-07-14 16:02:31','2022-07-15 16:02:31','30937333','RECEIPT KAS KELUAR',1),(21,3,'2022-07-15 16:53:51','2022-07-16 16:53:51','51321710','RECEIPT KAS KELUAR',1),(22,2,'2022-07-16 19:35:35','2022-07-17 19:35:35','35825086','RECEIPT KAS KELUAR',0),(23,3,'2022-07-16 19:53:03','2022-07-17 19:53:03','03622016','RECEIPT KAS MASUK',0),(24,5,'2022-07-16 19:54:13','2022-07-17 19:54:13','13286001','RECEIPT KAS KELUAR',0),(25,6,'2022-07-16 20:10:40','2022-07-17 20:10:40','40778482','RECEIPT KAS KELUAR',0);

/*Table structure for table `cetaklapkeuangan` */

DROP TABLE IF EXISTS `cetaklapkeuangan`;

CREATE TABLE `cetaklapkeuangan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `it` datetime DEFAULT NULL,
  `exp` datetime DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `jenis` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `pfilter1` varchar(50) DEFAULT NULL,
  `pfilter2` varchar(100) DEFAULT NULL,
  `pfilter3` varchar(100) DEFAULT NULL,
  `pfilter4` varchar(200) DEFAULT NULL,
  `periode` varchar(6) DEFAULT NULL,
  `aksi` varchar(10) DEFAULT 'PDF',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;

/*Data for the table `cetaklapkeuangan` */

insert  into `cetaklapkeuangan`(`id`,`it`,`exp`,`token`,`jenis`,`status`,`pfilter1`,`pfilter2`,`pfilter3`,`pfilter4`,`periode`,`aksi`) values (1,'2021-05-06 13:56:01','2021-05-07 13:56:01','a90aa49580933e836c96d34f5cec8358','Lap Neraca',0,'YES','','','','202105','PDF'),(2,'2021-05-06 14:01:16','2021-05-07 14:01:16','629eb1af8c0eab0ca93ca795e8ad740a','Lap Jurnal Umum',0,'YES','','','','202104','PDF'),(3,'2021-05-06 14:02:52','2021-05-07 14:02:52','8a5e0bdfdd08d3df8b3058e6310f5e0a','Lap Neraca',0,'YES','','','','202104','PDF'),(4,'2021-05-06 14:03:01','2021-05-07 14:03:01','5d550cb7012382323cbde587d3d755d0','Lap Neraca',0,'YES','','','','202104','PDF'),(5,'2021-05-07 11:27:30','2021-05-08 11:27:30','f721b9bf82667b5c13992135a1cb3d18','Lap Neraca',0,'YES','','','','202105','PDF'),(6,'2021-05-07 11:27:52','2021-05-08 11:27:52','6eeda6f8e2b945134e3ed5ccbf897ef5','Lap Neraca',0,'YES','','','','202105','PDF'),(7,'2021-05-07 11:28:18','2021-05-08 11:28:18','fa71658741da965f3417e4d4e1577a59','Lap Neraca',0,'YES','','','','202105','PDF'),(8,'2021-05-07 12:46:07','2021-05-08 12:46:07','9329bf8005800ccda09f1e4b0b04fe72','Lap Neraca',0,'YES','','','','202105','PDF'),(9,'2021-05-10 08:54:46','2021-05-11 08:54:46','709941019e094a837f4524f658b358dc','Lap Neraca',0,'YES','','','','202105','PDF'),(10,'2021-05-10 10:26:14','2021-05-11 10:26:14','31d9f1873660e656d6d3c26aa3a91e51','Lap Neraca',0,'TIDAK','','','','202105','PDF'),(11,'2021-05-10 11:34:30','2021-05-11 11:34:30','8cc5f280ad4440f5efe330a6049059e9','Lap Neraca',0,'TIDAK','','','','202105','PDF'),(12,'2021-05-10 13:14:23','2021-05-11 13:14:23','05e4b87d29618f91c2d76687a0c0a532','Lap Neraca',0,'YES','','','','202105','PDF'),(13,'2021-05-10 13:24:41','2021-05-11 13:24:41','a0329785f71accdbde630e4809b52a98','Lap Neraca',0,'TIDAK','','','','202105','PDF'),(14,'2021-05-10 14:18:31','2021-05-11 14:18:31','9f2b9870ab733b95b4e7e5a20c587c52','Lap Neraca Lajur',0,'TIDAK','','','','202105','PDF'),(15,'2021-05-11 09:05:32','2021-05-12 09:05:32','643fba2b6ad56a4e35f53d65b3737ffa','Lap Rugi Laba',0,'YES','','','','202105','PDF'),(16,'2021-05-11 09:05:58','2021-05-12 09:05:58','3203218eded9e52e23229b032251d372','Lap Rugi Laba',0,'TIDAK','','','','202105','PDF'),(17,'2021-05-11 09:07:28','2021-05-12 09:07:28','1232979a74b91ae4ac71266aba8a9d26','Lap Neraca',0,'YES','','','','202105','PDF'),(18,'2021-05-11 09:07:58','2021-05-12 09:07:58','82d8a289df31fd679232be133d7cdfd1','Lap Rugi Laba',0,'YES','','','','202105','PDF'),(19,'2021-05-11 09:09:12','2021-05-12 09:09:12','0b58b08e0de2c54d9b8877e45160bf09','Lap Rugi Laba',0,'YES','','','','202105','PDF'),(20,'2021-05-11 09:22:49','2021-05-12 09:22:49','b0502d22d88282e12931dba26dc8d93e','Lap Rugi Laba',0,'TIDAK','','','','202105','PDF'),(21,'2021-05-11 09:59:54','2021-05-12 09:59:54','c166ff5f17af593c994b66cd72785533','Lap Neraca',0,'YES','','','','202105','PDF'),(22,'2021-05-11 09:59:59','2021-05-12 09:59:59','66a1c145461cefb5c882df56bfd3b3a8','Lap Neraca Lajur',0,'YES','','','','202105','PDF'),(23,'2021-05-11 10:00:03','2021-05-12 10:00:03','c9b1bc7ca6abe941159130435e3172d9','Lap Rugi Laba',0,'YES','','','','202105','PDF'),(24,'2021-05-11 10:00:08','2021-05-12 10:00:08','42fdf0dd21e5fa8e9cf58dc88cce6829','Lap Buku Besar',0,'YES','','','','202105','PDF'),(25,'2021-05-11 10:05:43','2021-05-12 10:05:43','4d6d774c91b6e209c2c8120e3b1ded60','Lap Buku Besar',0,'YES','','','','202105','PDF'),(26,'2021-05-11 10:30:47','2021-05-12 10:30:47','71018ab0ac8946a6cf7a5787dd8f60e5','Lap Neraca',0,'TIDAK','','','','202105','PDF'),(27,'2021-05-11 10:31:07','2021-05-12 10:31:07','754ce89a954e98ad798db424a29c9daf','Lap Buku Besar',0,'TIDAK','norek','Sama Dengan','11201','202105','PDF'),(28,'2021-05-11 10:52:46','2021-05-12 10:52:46','8af001c60e5647094d400fc1788a12ca','Lap Buku Besar',0,'TIDAK','norek','Sama Dengan','11201','202105','PDF'),(29,'2021-05-11 11:23:01','2021-05-12 11:23:01','3c767e487b735612a446b53f71cdcea2','Lap Neraca',0,'TIDAK','norekdebet','Sama Dengan','11201','202105','PDF'),(30,'2021-05-11 11:24:53','2021-05-12 11:24:53','a9987c42c6c0ef854fa582661228e5c8','Lap Rugi Laba',0,'TIDAK','norekdebet','Sama Dengan','11201','202105','PDF'),(31,'2021-05-11 11:25:13','2021-05-12 11:25:13','9aff93b9198a276bd91eb710d08aa6bb','Lap Rugi Laba',0,'TIDAK','norek','Sama Dengan','11201','202105','PDF'),(32,'2021-05-11 11:26:23','2021-05-12 11:26:23','93bdd94c773f81a46b51760dd4805aa1','Lap Rugi Laba',0,'TIDAK','Pilih Field','Sama Dengan','','202105','PDF'),(33,'2021-05-11 11:27:22','2021-05-12 11:27:22','816145dbb2dbb2e6df61241514f65a2f','Lap Neraca Lajur',0,'TIDAK','Pilih Field','Sama Dengan','','202105','PDF'),(34,'2021-05-11 11:27:38','2021-05-12 11:27:38','26592dcbb68f36093dc841ac38348f4e','Lap Neraca Lajur',0,'TIDAK','nomor','Sama Dengan','23103','202105','PDF'),(35,'2021-05-11 11:32:30','2021-05-12 11:32:30','cd55440a2ac505acff9c03289d8fc82c','Lap Neraca',0,'TIDAK','norekdebet','Sama Dengan','23103','202105','PDF'),(36,'2021-05-11 11:34:34','2021-05-12 11:34:34','41a733599303060d7e8ca68c5b07937f','Lap Neraca',0,'YES','norekdebet','Sama Dengan','','202105','PDF'),(37,'2021-05-11 11:35:12','2021-05-12 11:35:12','93199d6dba0246582740543ef2162ff4','Lap Neraca',0,'YES','norekdebet','Sama Dengan','11201','202105','PDF'),(38,'2021-05-11 11:35:46','2021-05-12 11:35:46','d6c742dae628d7b15a625ec79a8687ea','Lap Neraca',0,'TIDAK','norekdebet','Sama Dengan','','202105','PDF'),(39,'2021-05-11 11:51:26','2021-05-12 11:51:26','4bc0927287ba7896c0b1ef2ee068fd59','Lap Jurnal Umum',0,'YES','norekdebet','Sama Dengan','','202105','PDF'),(40,'2021-05-11 11:53:39','2021-05-12 11:53:39','50ddcab59c85eff7482307ac9f834c5a','Lap Jurnal Umum',0,'YES','nobukti','Sama Dengan','KAS_2021051000000001','202105','PDF'),(41,'2021-05-11 11:54:23','2021-05-12 11:54:23','b672d71760cbdd9852637da16065f476','Lap Jurnal Umum',0,'YES','nobukti','Sama Dengan','KAS_2021051000000001','202105','PDF'),(42,'2021-05-11 11:54:42','2021-05-12 11:54:42','1ba2a7ca66000c8e4d25880787e1bd68','Lap Jurnal Umum',0,'YES','nobukti','Sama Dengan','','202105','PDF'),(43,'2021-05-11 12:01:21','2021-05-12 12:01:21','f9d3a9401cd4f3d289da1c3248ab8d05','Lap Neraca',0,'YES','nobukti','Sama Dengan','','202105','PDF'),(44,'2021-05-17 11:54:34','2021-05-18 11:54:34','42d3db5ec8a07e8a0f7bc65fcbdf47b6','Lap Jurnal Umum',0,'YES','','','','202105','PDF'),(45,'2021-05-17 13:57:28','2021-05-18 13:57:28','da6d29e91ee444e0f96643c2708612ae','Lap Buku Besar',0,'YES','norek','Sama Dengan','11201','202105','PDF'),(46,'2021-05-17 13:57:46','2021-05-18 13:57:46','67409d0f98822088574e78f37c7b9c10','Lap Neraca',0,'YES','norek','Sama Dengan','','202105','PDF'),(47,'2021-05-17 14:02:30','2021-05-18 14:02:30','c4f5931e880b084634502dcbbc84d2e7','Lap Neraca Lajur',0,'YES','nomor','','','202105','PDF'),(48,'2021-06-02 15:57:00','2021-06-03 15:57:00','a1f244a8e870f3686262c3205f20040e','Lap Neraca',0,'YES','','','','202106','PDF'),(49,'2021-11-04 09:23:20','2021-11-05 09:23:20','9cad8ec612c0c4434c26761393d42428','Lap Neraca',0,'YES','','','','202111','PDF'),(50,'2021-11-04 13:56:27','2021-11-05 13:56:27','617200cf2dd5ad58fd44d7e766951037','Lap Neraca',0,'YES','','','','202111','PDF'),(51,'2022-07-15 15:13:00','2022-07-16 15:13:00','20220715151300807641','Buku Besar',0,'YES','','','','202207','PDF'),(52,'2022-07-15 15:16:01','2022-07-16 15:16:01','20220715151601116954','Jurnal Umum',0,'YES','','','','202207','PDF'),(53,'2022-07-15 15:44:43','2022-07-16 15:44:43','20220715154443876490','Jurnal Umum',0,'YES','','','','202207','PDF'),(54,'2022-07-15 15:45:40','2022-07-16 15:45:40','20220715154540028702','Jurnal Umum',0,'YES','','','','202207','PDF'),(55,'2022-07-15 15:48:51','2022-07-16 15:48:51','20220715154851624660','Buku Besar',0,'YES','','','','202207','PDF'),(56,'2022-07-15 15:53:36','2022-07-16 15:53:36','20220715155336885976','Neraca Lajur',0,'YES','','','','202207','PDF'),(57,'2022-07-15 15:53:47','2022-07-16 15:53:47','20220715155347637591','Laba Rugi',0,'YES','','','','202207','PDF'),(58,'2022-07-15 15:54:05','2022-07-16 15:54:05','20220715155405406608','Neraca',0,'YES','','','','202207','PDF'),(59,'2022-07-15 15:56:57','2022-07-16 15:56:57','20220715155657904474','Neraca',0,'YES','','','','202207','PDF'),(60,'2022-07-15 15:58:40','2022-07-16 15:58:40','20220715155840830361','Jurnal Umum',0,'YES','','','','202207','PDF'),(61,'2022-07-15 16:02:06','2022-07-16 16:02:06','20220715160206394119','Neraca Lajur',0,'YES','','','','202207','PDF'),(62,'2022-07-15 16:03:49','2022-07-16 16:03:49','20220715160349125994','Laba Rugi',0,'YES','','','','202207','PDF'),(63,'2022-07-15 16:04:30','2022-07-16 16:04:30','20220715160430719373','Neraca',0,'YES','','','','202207','PDF'),(64,'2022-07-15 16:04:43','2022-07-16 16:04:43','20220715160443158085','Jurnal Umum',0,'YES','','','','202207','PDF'),(65,'2022-07-15 16:04:51','2022-07-16 16:04:51','20220715160451518563','Buku Besar',0,'YES','','','','202207','PDF'),(66,'2022-07-15 16:19:08','2022-07-16 16:19:08','20220715161908756594','Jurnal Umum',0,'YES','','','','202207','PDF'),(67,'2022-07-15 16:19:12','2022-07-16 16:19:12','20220715161912513809','Buku Besar',0,'YES','','','','202207','PDF'),(68,'2022-07-15 16:19:15','2022-07-16 16:19:15','20220715161915590985','Neraca Lajur',0,'YES','','','','202207','PDF'),(69,'2022-07-15 16:19:21','2022-07-16 16:19:21','20220715161921845343','Laba Rugi',0,'YES','','','','202207','PDF'),(70,'2022-07-15 16:19:25','2022-07-16 16:19:25','20220715161925033525','Laba Rugi',0,'YES','','','','202207','PDF'),(71,'2022-07-15 16:19:28','2022-07-16 16:19:28','20220715161928487723','Neraca',0,'YES','','','','202207','PDF'),(72,'2022-07-16 19:32:22','2022-07-17 19:32:22','20220716193222567032','Buku Besar',0,'YES','','','','202207','PDF'),(73,'2022-07-16 19:33:41','2022-07-17 19:33:41','20220716193341131526','Neraca Lajur',0,'YES','','','','202207','PDF'),(74,'2022-07-16 19:33:54','2022-07-17 19:33:54','20220716193354004262','Laba Rugi',0,'YES','','','','202207','PDF'),(75,'2022-07-16 19:53:22','2022-07-17 19:53:22','20220716195322813114','Laba Rugi',0,'YES','','','','202207','PDF'),(76,'2022-07-16 19:53:30','2022-07-17 19:53:30','20220716195330853574','Neraca',0,'YES','','','','202207','PDF'),(77,'2022-07-16 19:56:49','2022-07-17 19:56:49','20220716195649596941','Buku Besar',0,'YES','','','','202207','PDF'),(78,'2022-07-16 19:57:21','2022-07-17 19:57:21','20220716195721487766','Neraca',0,'YES','','','','202207','PDF'),(79,'2022-07-16 19:57:31','2022-07-17 19:57:31','20220716195731781354','Neraca',0,'YES','','','','202208','PDF'),(80,'2022-07-16 19:58:04','2022-07-17 19:58:04','20220716195804326216','Neraca',0,'YES','','','','202208','PDF'),(81,'2022-07-16 19:58:10','2022-07-17 19:58:10','20220716195810079545','Buku Besar',0,'YES','','','','202208','PDF'),(82,'2022-07-16 19:58:26','2022-07-17 19:58:26','20220716195826942509','Laba Rugi',0,'YES','','','','202208','PDF'),(83,'2022-07-16 20:09:32','2022-07-17 20:09:32','20220716200932380570','Neraca',0,'YES','','','','202208','PDF'),(84,'2022-07-16 20:10:05','2022-07-17 20:10:05','20220716201005149444','Buku Besar',0,'YES','','','','202207','PDF'),(85,'2022-07-16 20:32:46','2022-07-17 20:32:46','20220716203246192292','Jurnal Umum',0,'YES','','','','202207','PDF'),(86,'2022-07-16 20:32:54','2022-07-17 20:32:54','20220716203254726780','Buku Besar',0,'YES','','','','202207','PDF'),(87,'2022-07-16 20:33:50','2022-07-17 20:33:50','20220716203350387963','Jurnal Umum',0,'YES','','','','202207','PDF'),(88,'2022-07-16 20:34:31','2022-07-17 20:34:31','20220716203431806332','Jurnal Umum',0,'YES','','','','202207','PDF'),(89,'2022-07-16 20:34:35','2022-07-17 20:34:35','20220716203435318533','Buku Besar',0,'YES','','','','202207','PDF'),(90,'2022-07-16 20:34:47','2022-07-17 20:34:47','20220716203447206213','Laba Rugi',0,'YES','','','','202207','PDF'),(91,'2022-07-16 20:39:18','2022-07-17 20:39:18','20220716153918316720','Jurnal Umum',0,'YES','','','','202207','PDF'),(92,'2022-07-16 20:48:49','2022-07-17 20:48:49','20220716154848966359','Buku Besar',0,'YES','','','','202207','PDF'),(93,'2022-07-16 20:48:55','2022-07-17 20:48:55','20220716154854794693','Buku Besar',0,'YES','','','','202209','PDF');

/*Table structure for table `hakakses` */

DROP TABLE IF EXISTS `hakakses`;

CREATE TABLE `hakakses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(25) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `urutan` int(11) DEFAULT '0',
  `jenis` varchar(50) DEFAULT NULL,
  `username` int(11) NOT NULL,
  `lihat` smallint(6) DEFAULT '0',
  `grup` varchar(3) NOT NULL DEFAULT '100',
  `kategori` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  CONSTRAINT `hakakses_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4975 DEFAULT CHARSET=latin1;

/*Data for the table `hakakses` */

insert  into `hakakses`(`id`,`kode`,`nama`,`urutan`,`jenis`,`username`,`lihat`,`grup`,`kategori`) values (6,'perkiraan','Perkiraan',2,'Master',1,1,'100','Master'),(7,'bank','Bank',0,'Master',1,1,'100','Master'),(16,'pengguna','Pengguna',1,'Master',1,1,'100','Master'),(24,'jurnalumum','Jurnal Umum',13,'Transaksi',1,1,'200','Transaksi'),(57,'setting','Setting',0,'Setting',1,1,'400','Setting'),(598,'laporankeuangan','Laba Rugi',5002,'Laporan Keuangan',1,1,'300','Laporan'),(599,'laporankeuangan','Buku Besar',5003,'Laporan Keuangan',1,1,'300','Laporan'),(600,'laporankeuangan','Jurnal Umum',5004,'Laporan Keuangan',1,1,'300','Laporan'),(4925,'kask','Transaksi Kas Keluar',11,'Transaksi',1,1,'200','Transaksi'),(4926,'kasm','Transaksi Kas Masuk',12,'Transaksi',1,1,'200','Transaksi'),(4927,'bank','Bank',0,'Master',2,1,'100','Master'),(4928,'pengguna','Pengguna',1,'Master',2,1,'100','Master'),(4929,'perkiraan','Perkiraan',2,'Master',2,1,'100','Master'),(4930,'kask','Transaksi Kas Keluar',11,'Transaksi',2,1,'200','Transaksi'),(4931,'kasm','Transaksi Kas Masuk',12,'Transaksi',2,1,'200','Transaksi'),(4932,'jurnalumum','Jurnal Umum',13,'Transaksi',2,1,'200','Transaksi'),(4937,'laporankeuangan','Laba Rugi',5002,'Laporan Keuangan',2,1,'300','Laporan'),(4938,'laporankeuangan','Buku Besar',5003,'Laporan Keuangan',2,1,'300','Laporan'),(4939,'laporankeuangan','Jurnal Umum',5004,'Laporan Keuangan',2,1,'300','Laporan'),(4940,'setting','Setting',0,'Setting',2,1,'400','Setting'),(4944,'bank','Bank',0,'Master',3,1,'100','Master'),(4945,'pengguna','Pengguna',1,'Master',3,1,'100','Master'),(4946,'perkiraan','Perkiraan',2,'Master',3,1,'100','Master'),(4947,'kask','Transaksi Kas Keluar',11,'Transaksi',3,1,'200','Transaksi'),(4948,'kasm','Transaksi Kas Masuk',12,'Transaksi',3,1,'200','Transaksi'),(4949,'jurnalumum','Jurnal Umum',13,'Transaksi',3,1,'200','Transaksi'),(4954,'laporankeuangan','Laba Rugi',5002,'Laporan Keuangan',3,1,'300','Laporan'),(4955,'laporankeuangan','Buku Besar',5003,'Laporan Keuangan',3,1,'300','Laporan'),(4956,'laporankeuangan','Jurnal Umum',5004,'Laporan Keuangan',3,1,'300','Laporan'),(4957,'setting','Setting',0,'Setting',3,1,'400','Setting'),(4961,'bank','Bank',0,'Master',4,1,'100','Master'),(4962,'pengguna','Pengguna',1,'Master',4,1,'100','Master'),(4963,'perkiraan','Perkiraan',2,'Master',4,1,'100','Master'),(4964,'kask','Transaksi Kas Keluar',11,'Transaksi',4,1,'200','Transaksi'),(4965,'kasm','Transaksi Kas Masuk',12,'Transaksi',4,1,'200','Transaksi'),(4966,'jurnalumum','Jurnal Umum',13,'Transaksi',4,1,'200','Transaksi'),(4971,'laporankeuangan','Laba Rugi',5002,'Laporan Keuangan',4,1,'300','Laporan'),(4972,'laporankeuangan','Buku Besar',5003,'Laporan Keuangan',4,1,'300','Laporan'),(4973,'laporankeuangan','Jurnal Umum',5004,'Laporan Keuangan',4,1,'300','Laporan'),(4974,'setting','Setting',0,'Setting',4,1,'400','Setting');

/*Table structure for table `jurnal_d` */

DROP TABLE IF EXISTS `jurnal_d`;

CREATE TABLE `jurnal_d` (
  `idh` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `norek` int(11) NOT NULL,
  `debet` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kredit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` varchar(100) DEFAULT NULL,
  `deskripsi` varchar(100) DEFAULT NULL,
  `tabelid` int(11) NOT NULL,
  `reposting` int(11) DEFAULT '0',
  `hapus` int(11) DEFAULT '0',
  `dt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idh` (`idh`),
  KEY `norek` (`norek`),
  CONSTRAINT `jurnal_d_ibfk_1` FOREIGN KEY (`idh`) REFERENCES `jurnal_h` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jurnal_d_ibfk_2` FOREIGN KEY (`norek`) REFERENCES `perkiraan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

/*Data for the table `jurnal_d` */

insert  into `jurnal_d`(`idh`,`id`,`norek`,`debet`,`kredit`,`keterangan`,`deskripsi`,`tabelid`,`reposting`,`hapus`,`dt`) values (8,37,53,0.00,500000.00,'JASA SERVIS AC 5 UNIT','KAS REK KAS KAS MASUK',7,0,0,NULL),(8,38,609,500000.00,0.00,'JASA SERVIS AC 5 UNIT','KAS REK BANK KAS MASUK',7,0,0,NULL),(9,39,61,7650.00,0.00,'BENSIN 1 LITER','KAS REK KAS KAS KELUAR',9,0,0,NULL),(9,40,609,0.00,7650.00,'BENSIN 1 LITER','KAS REK BANK KAS KELUAR',9,0,0,NULL),(10,41,77,150000.00,0.00,'bayar listrik','KAS REK KAS KAS KELUAR',10,0,0,NULL),(10,42,609,0.00,150000.00,'bayar listrik','KAS REK BANK KAS KELUAR',10,0,0,NULL);

/*Table structure for table `jurnal_h` */

DROP TABLE IF EXISTS `jurnal_h`;

CREATE TABLE `jurnal_h` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nobukti` varchar(50) NOT NULL,
  `tgl` date DEFAULT NULL,
  `periode` varchar(6) NOT NULL,
  `jenis` varchar(20) NOT NULL DEFAULT 'UMUM',
  `idsumber` int(11) NOT NULL DEFAULT '0',
  `totaldebet` decimal(15,2) DEFAULT '0.00',
  `totalkredit` decimal(15,2) DEFAULT '0.00',
  `dperiode` varchar(8) DEFAULT NULL,
  `it` datetime DEFAULT NULL,
  `et` datetime DEFAULT NULL,
  `dt` datetime DEFAULT NULL,
  `hapus` int(11) DEFAULT '0',
  `tabel` varchar(20) DEFAULT 'UMUM',
  `userid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `jurnal_h` */

insert  into `jurnal_h`(`id`,`nobukti`,`tgl`,`periode`,`jenis`,`idsumber`,`totaldebet`,`totalkredit`,`dperiode`,`it`,`et`,`dt`,`hapus`,`tabel`,`userid`) values (8,'K_2022071600000001','2022-07-16','202207','UMUM',3,500000.00,500000.00,'20220716','2022-07-16 19:53:00',NULL,NULL,0,'KAS MASUK',4),(9,'K_2022071600000001','2022-07-16','202207','UMUM',5,7650.00,7650.00,'20220716','2022-07-16 19:54:11',NULL,NULL,0,'KAS KELUAR',4),(10,'K_2022071600000002','2022-07-16','202207','UMUM',6,150000.00,150000.00,'20220716','2022-07-16 20:10:38',NULL,NULL,0,'KAS KELUAR',4);

/*Table structure for table `log` */

DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `it` datetime DEFAULT NULL,
  `grup` varchar(100) DEFAULT NULL,
  `desk` text,
  `dperiode` varchar(8) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `api` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `log` */

/*Table structure for table `loglogin` */

DROP TABLE IF EXISTS `loglogin`;

CREATE TABLE `loglogin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `ip` varchar(25) DEFAULT NULL,
  `it` datetime DEFAULT NULL,
  `ot` datetime DEFAULT NULL,
  `expdperiode` varchar(25) DEFAULT '0',
  `status` varchar(3) DEFAULT 'on',
  `token` varchar(8) NOT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `loglogin_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=625 DEFAULT CHARSET=latin1;

/*Data for the table `loglogin` */

insert  into `loglogin`(`id`,`user`,`ip`,`it`,`ot`,`expdperiode`,`status`,`token`,`data`) values (621,4,'::1','2022-07-16 19:52:12',NULL,'20220716221220','on','96568260','{\"id\":\"4\",\"nama\":\"agus\",\"nickname\":\"agus\",\"password\":\"agus\",\"it\":\"2022-07-13 09:11:44\",\"et\":null,\"dt\":\"2022-07-13 09:21:08\",\"hapus\":\"0\"}'),(622,4,'::1','2022-07-16 20:28:49',NULL,'20220716223350','on','42601830','{\"id\":\"4\",\"nama\":\"agus\",\"nickname\":\"agus\",\"password\":\"agus\",\"it\":\"2022-07-13 09:11:44\",\"et\":null,\"dt\":\"2022-07-13 09:21:08\",\"hapus\":\"0\"}'),(623,4,'::1','2022-07-16 20:34:23',NULL,'20220716223918','on','32719260','{\"id\":\"4\",\"nama\":\"agus\",\"nickname\":\"agus\",\"password\":\"agus\",\"it\":\"2022-07-13 09:11:44\",\"et\":null,\"dt\":\"2022-07-13 09:21:08\",\"hapus\":\"0\"}'),(624,4,'::1','2022-07-16 20:45:44',NULL,'20220716224854','on','11847919','{\"id\":\"4\",\"nama\":\"agus\",\"nickname\":\"agus\",\"password\":\"agus\",\"it\":\"2022-07-13 09:11:44\",\"et\":null,\"dt\":\"2022-07-13 09:21:08\",\"hapus\":\"0\"}');

/*Table structure for table `menutemp` */

DROP TABLE IF EXISTS `menutemp`;

CREATE TABLE `menutemp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(25) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `username` int(11) NOT NULL,
  `urutan` int(11) DEFAULT '0',
  `jenis` varchar(50) DEFAULT NULL,
  `grup` varchar(3) NOT NULL DEFAULT '100',
  `kategori` varchar(50) DEFAULT 'Master',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  CONSTRAINT `menutemp_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `menutemp` */

/*Table structure for table `perkiraan` */

DROP TABLE IF EXISTS `perkiraan`;

CREATE TABLE `perkiraan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(9) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `levelno` int(11) DEFAULT NULL,
  `nomorheader` int(11) DEFAULT '0',
  `posisidk` varchar(6) NOT NULL,
  `posisinr` varchar(6) NOT NULL,
  `jenisjurnal` varchar(10) DEFAULT NULL,
  `posisineraca` varchar(10) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `jenislevel` varchar(10) NOT NULL,
  `link` varchar(15) DEFAULT NULL,
  `userid` int(11) DEFAULT '0',
  `it` datetime DEFAULT NULL,
  `et` datetime DEFAULT NULL,
  `dt` datetime DEFAULT NULL,
  `hapus` int(11) DEFAULT '0',
  `search` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3530 DEFAULT CHARSET=latin1;

/*Data for the table `perkiraan` */

insert  into `perkiraan`(`id`,`nomor`,`nama`,`levelno`,`nomorheader`,`posisidk`,`posisinr`,`jenisjurnal`,`posisineraca`,`keterangan`,`jenislevel`,`link`,`userid`,`it`,`et`,`dt`,`hapus`,`search`) values (1,'1','A K T I V A',1,0,'DEBET','NERACA','','DEBET','aktiva','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'1 || A K T I V A'),(2,'11000','AKTIVA LANCAR',2,1,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,'2022-04-14 11:21:59','2022-06-20 13:24:05',NULL,0,'11000 || AKTIVA LANCAR'),(3,'11100','KAS DAN BANK',3,2,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11100 || KAS DAN BANK'),(4,'11101','KAS',4,3,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11101 || KAS'),(5,'11200','BANK',3,2,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11200 || BANK'),(6,'11201','BANK',4,5,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11201 || BANK'),(7,'11300','SIMPANAN BERHARGA',3,2,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11300 || SIMPANAN BERHARGA'),(8,'11301','SIMPANAN BERHARGA',4,7,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11301 || SIMPANAN BERHARGA'),(9,'11400','PIUTANG DAGANG',3,2,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11400 || PIUTANG DAGANG'),(10,'11401','PIUTANG DAGANG',4,9,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11401 || PIUTANG DAGANG'),(11,'11500','PIUTANG KARYAWAN',3,2,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11500 || PIUTANG KARYAWAN'),(12,'11501','PIUTANG KARYAWAN',4,11,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11501 || PIUTANG KARYAWAN'),(13,'11600','PIUTANG LAIN-LAIN',3,2,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11600 || PIUTANG LAIN-LAIN'),(14,'11601','PIUTANG LAIN-LAIN',4,13,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11601 || PIUTANG LAIN-LAIN'),(15,'11900','STOCK BARANG',3,2,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11900 || STOCK BARANG'),(16,'11901','PERSEDIAAN BARANG DAGANGAN',4,15,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'11901 || PERSEDIAAN BARANG DAGANGAN'),(17,'12000','AKTIVA TETAP',2,1,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'12000 || AKTIVA TETAP'),(18,'12100','TANAH DAN BANGUNAN',3,17,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'12100 || TANAH DAN BANGUNAN'),(19,'12101','TANAH DAN BANGUNAN',4,18,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'12101 || TANAH DAN BANGUNAN'),(20,'12200','AKUMULASI TANAH & BANGUNAN',3,17,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'12200 || AKUMULASI TANAH & BANGUNAN'),(22,'12300','KENDARAAN BERMOTOR',3,17,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'12300 || KENDARAAN BERMOTOR'),(23,'12301','KENDARAAN BERMOTOR',4,22,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'12301 || KENDARAAN BERMOTOR'),(24,'12400','AKUMULASI PH KENDARAAN MOTOR',3,17,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'12400 || AKUMULASI PH KENDARAAN MOTOR'),(25,'12401','AKUMULASI KENDARAAN BERMOTOR',4,24,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'12401 || AKUMULASI KENDARAAN BERMOTOR'),(26,'12500','INVENTARIS KANTOR',3,17,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'12500 || INVENTARIS KANTOR'),(27,'12501','INVENTARIS KANTOR',4,26,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'12501 || INVENTARIS KANTOR'),(28,'12600','AKUMULASI PH INVENTARIS KANTOR',3,17,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'12600 || AKUMULASI PH INVENTARIS KANTOR'),(29,'12601','AKUMULASI INVENTARIS KANTOR',4,28,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'12601 || AKUMULASI INVENTARIS KANTOR'),(30,'13000','AKTIVA LAIN-LAIN',2,1,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'13000 || AKTIVA LAIN-LAIN'),(31,'13100','PRIVE PERSERO',3,30,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'13100 || PRIVE PERSERO'),(32,'13101','PRIVE PERSERO',4,31,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'13101 || PRIVE PERSERO'),(33,'13200','PAJAK DIBAYAR DIMUKA',3,30,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'13200 || PAJAK DIBAYAR DIMUKA'),(34,'13201','PAJAK DIBAYAR DIMUKA',4,33,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'13201 || PAJAK DIBAYAR DIMUKA'),(35,'2','PASSIVA',1,0,'KREDIT','NERACA','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'2 || PASSIVA'),(36,'21000','PASIVA LANCAR',2,35,'KREDIT','NERACA','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'21000 || PASIVA LANCAR'),(37,'21100','HUTANG BANK',3,36,'KREDIT','NERACA','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'21100 || HUTANG BANK'),(38,'21101','HUTANG BANK',4,37,'KREDIT','NERACA','','KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'21101 || HUTANG BANK'),(39,'21200','HUTANG DAGANG',3,36,'KREDIT','NERACA','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'21200 || HUTANG DAGANG'),(40,'21201','HUTANG DAGANG',4,39,'KREDIT','NERACA','','KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'21201 || HUTANG DAGANG'),(41,'21300','HUTANG LAIN-LAIN',3,36,'KREDIT','NERACA','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'21300 || HUTANG LAIN-LAIN'),(42,'21301','HUTANG LAIN-LAIN',4,41,'KREDIT','NERACA','','KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'21301 || HUTANG LAIN-LAIN'),(43,'21400','HUTANG PAJAK',3,36,'KREDIT','NERACA','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'21400 || HUTANG PAJAK'),(44,'21401','HUTANG PAJAK',4,43,'KREDIT','NERACA','','KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'21401 || HUTANG PAJAK'),(45,'23000','MODAL SENDIRI',2,35,'KREDIT','NERACA','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'23000 || MODAL SENDIRI'),(46,'23100','M O D A L',3,45,'KREDIT','NERACA','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'23100 || M O D A L'),(47,'23101','MODAL DISETOR',4,46,'KREDIT','NERACA','','KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'23101 || MODAL DISETOR'),(48,'31105','RETUR PEMBELIAN',4,52,'KREDIT','R/L',NULL,'KREDIT',NULL,'DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'31105 || RETUR PEMBELIAN'),(49,'31106','PEND RETUR PEMBELIAN',4,52,'KREDIT','R/L',NULL,'KREDIT',NULL,'DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'31106 || PEND RETUR PEMBELIAN'),(50,'3','PENDAPATAN DAN HARGA POKOK',1,0,'KREDIT','R/L','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'3 || PENDAPATAN DAN HARGA POKOK'),(51,'31000','PENDAPATAN',2,50,'KREDIT','R/L','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'31000 || PENDAPATAN'),(52,'31100','PENJUALAN BARANG',3,51,'KREDIT','R/L','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'31100 || PENJUALAN BARANG'),(53,'31101','PENJUALAN BARANG DAGANGAN',4,52,'KREDIT','R/L','','KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'31101 || PENJUALAN BARANG DAGANGAN'),(54,'31102','DISCOUNT PENJUALAN',4,52,'KREDIT','R/L','','KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'31102 || DISCOUNT PENJUALAN'),(55,'31103','RETUR PENJUALAN',4,52,'KREDIT','R/L','','KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'31103 || RETUR PENJUALAN'),(56,'31104','HARGA POKOK PENJUALAN',4,52,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'31104 || HARGA POKOK PENJUALAN'),(57,'4','BIAYA-BIAYA',1,0,'DEBET','R/L','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'4 || BIAYA-BIAYA'),(58,'41000','BIAYA PENJUALAN',2,57,'DEBET','R/L','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'41000 || BIAYA PENJUALAN'),(59,'41100','BIAYA PENJUALAN',3,58,'DEBET','R/L','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'41100 || BIAYA PENJUALAN'),(60,'41101','BIAYA PENGIRIMAN+SEWA ANGKUTAN',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41101 || BIAYA PENGIRIMAN+SEWA ANGKUTAN'),(61,'41102','BENSIN/SOLAR',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41102 || BENSIN/SOLAR'),(62,'41103','SERVIS MOBIL/KENDARAAN',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41103 || SERVIS MOBIL/KENDARAAN'),(63,'41104','PERPANJANGAN STNK + SIM',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41104 || PERPANJANGAN STNK + SIM'),(64,'41105','BIAYA PENGINAPAN/LUAR KOTA',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41105 || BIAYA PENGINAPAN/LUAR KOTA'),(65,'41106','BIAYA LEMBUR',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41106 || BIAYA LEMBUR'),(66,'41107','UANG MAKAN',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41107 || UANG MAKAN'),(67,'41108','NGEMEL',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41108 || NGEMEL'),(68,'31107','BIAYA RETUR PEMBELIAN',4,52,'DEBET','R/L',NULL,'DEBET',NULL,'DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'31107 || BIAYA RETUR PEMBELIAN'),(69,'41109','KOMISI',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41109 || KOMISI'),(70,'41110','BIAYA ADMINISTRASI BANK',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41110 || BIAYA ADMINISTRASI BANK'),(71,'41111','BIAYA SEWA',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41111 || BIAYA SEWA'),(72,'41112','RETRIBUSI',4,59,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41112 || RETRIBUSI'),(73,'42000','BIAYA ADMINISTRASI DAN UMUM',2,57,'DEBET','R/L','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'42000 || BIAYA ADMINISTRASI DAN UMUM'),(74,'42100','BIAYA ADMINISTRASI DAN UMUM',3,73,'DEBET','R/L','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'42100 || BIAYA ADMINISTRASI DAN UMUM'),(75,'42101','GAJI',4,74,'DEBET','R/L','','KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'42101 || GAJI'),(77,'42103','TELEPON/LISTRIK/AIR',4,74,'DEBET','R/L','','KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'42103 || TELEPON/LISTRIK/AIR'),(79,'42105','PENGURUSAN NPWP+PEMBUATAN AKTA',4,74,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'42105 || PENGURUSAN NPWP+PEMBUATAN AKTA'),(81,'43000','BIAYA LAIN-LAIN',2,57,'DEBET','R/L','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'43000 || BIAYA LAIN-LAIN'),(82,'43100','BIAYA LAIN-LAIN',3,81,'DEBET','R/L','','DEBET','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'43100 || BIAYA LAIN-LAIN'),(83,'43101','BIAYA LAIN-LAIN',4,82,'DEBET','R/L','','DEBET','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'43101 || BIAYA LAIN-LAIN'),(84,'5','PENDAPATAN DAN BIAYA LAIN-LAIN',1,0,'KREDIT','R/L','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'5 || PENDAPATAN DAN BIAYA LAIN-LAIN'),(85,'51000','PENDAPATAN LAIN-LAIN',2,84,'KREDIT','R/L','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'51000 || PENDAPATAN LAIN-LAIN'),(86,'51100','PENDAPATAN LAIN-LAIN',3,85,'KREDIT','R/L','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'51100 || PENDAPATAN LAIN-LAIN'),(87,'51101','PENDAPATAN LAIN-LAIN',4,86,'KREDIT','R/L','','KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'51101 || PENDAPATAN LAIN-LAIN'),(88,'52000','PENDAPATAN PENJUALAN',2,84,'KREDIT','R/L','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'52000 || PENDAPATAN PENJUALAN'),(89,'52100','PENDAPATAN PENJUALAN',3,88,'KREDIT','R/L','','KREDIT','','JUDUL',NULL,1,NULL,'2022-06-20 13:24:05',NULL,0,'52100 || PENDAPATAN PENJUALAN'),(91,'11402','PIUTANG BG',4,9,'DEBET','NERACA',NULL,'DEBET',NULL,'DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'11402 || PIUTANG BG'),(92,'21202','HUTANG BG',4,39,'KREDIT','NERACA',NULL,'KREDIT',NULL,'DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'21202 || HUTANG BG'),(93,'11403','PIUTANG TITIPAN SUPLIER',4,9,'DEBET','NERACA',NULL,'DEBET',NULL,'DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'11403 || PIUTANG TITIPAN SUPLIER'),(94,'21203','PIUTANG TITIPAN CUSTOMER',4,39,'KREDIT','NERACA',NULL,'KREDIT',NULL,'DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'21203 || PIUTANG TITIPAN CUSTOMER'),(96,'23103','LABA PERIODE BERJALAN',4,46,'KREDIT','NERACA',NULL,'KREDIT',NULL,'DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'23103 || LABA PERIODE BERJALAN'),(97,'42107','BIAYA PENYUSUTAN AKTIVA',4,74,'DEBET','R/L',NULL,'DEBET',NULL,'DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'42107 || BIAYA PENYUSUTAN AKTIVA'),(98,'43102','STOK OPNAME FISIK KURANG',4,82,'DEBET','R/L',NULL,'DEBET',NULL,'DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'43102 || STOK OPNAME FISIK KURANG'),(100,'41113','BIAYA PEMAKAIAN',4,59,'DEBET','R/L',NULL,'DEBET','PEMAKAIAN','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'41113 || BIAYA PEMAKAIAN'),(101,'31108','PENDAPATAN AMAL',4,52,'KREDIT','R/L',NULL,'KREDIT','','DETIL',NULL,1,NULL,'2022-06-20 13:24:06',NULL,0,'31108 || PENDAPATAN AMAL'),(608,'11102','KAS BESAR',4,3,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,'2021-04-19 13:46:25',NULL,NULL,0,'11102 || KAS BESAR'),(609,'11103','KAS KECIL',4,3,'DEBET','NERACA','','DEBET','','JUDUL',NULL,1,'2021-04-19 13:53:09',NULL,NULL,0,'11103 || KAS KECIL'),(1381,'11106','KAS ',4,3,'DEBET','NERACA',NULL,'DEBET','','DETIL',NULL,4,'2021-05-31 14:31:12','2022-03-23 12:27:54',NULL,0,'11106 || KAS '),(1492,'12201','AKUMULASI TANAH DAN BANGUNAN',4,20,'DEBET','NERACA','','DEBET','','DETIL',NULL,1,'2022-02-10 10:02:57','2022-06-20 13:24:06',NULL,0,'12201 || AKUMULASI TANAH DAN BANGUNAN'),(1493,'42102','KEPERLUAN KANTOR',4,74,'DEBET','R/L','','KREDIT','','DETIL',NULL,1,'2022-02-10 10:02:58','2022-06-20 13:24:06',NULL,0,'42102 || KEPERLUAN KANTOR'),(1494,'42104','SERVIS PERALATAN',4,74,'DEBET','R/L','','KREDIT','','DETIL',NULL,1,'2022-02-10 10:02:58','2022-06-20 13:24:06',NULL,0,'42104 || SERVIS PERALATAN'),(1495,'42106','BONUS',4,74,'DEBET','R/L','','DEBET','','DETIL',NULL,1,'2022-02-10 10:02:58','2022-06-20 13:24:06',NULL,0,'42106 || BONUS'),(1499,'51102','STOK OPNAME FISIK LEBIH',4,86,'KREDIT','R/L',NULL,'KREDIT',NULL,'DETIL',NULL,1,'2022-02-10 11:34:49','2022-06-20 13:24:06',NULL,0,'51102 || STOK OPNAME FISIK LEBIH'),(3527,'52101','PENDAPATAN PENJUALAN',4,89,'KREDIT','R/L','','KREDIT','','DETIL',NULL,1,'2022-03-01 11:24:52','2022-06-20 13:24:06',NULL,0,'52101 || PENDAPATAN PENJUALAN'),(3528,'23102','LABA DITAHAN',4,46,'KREDIT','NERACA',NULL,'KREDIT',NULL,'DETIL',NULL,1,'2022-03-01 11:24:52','2022-06-20 13:24:06',NULL,0,'23102 || LABA DITAHAN'),(3529,'11107','KAS KECIL',4,3,'DEBET','NERACA',NULL,'DEBET','','DETIL',NULL,1,'2022-07-13 15:07:44',NULL,'2022-07-13 15:08:40',0,'11107 || KAS KECIL');

/*Table structure for table `perkiraan_saldo` */

DROP TABLE IF EXISTS `perkiraan_saldo`;

CREATE TABLE `perkiraan_saldo` (
  `nomor` int(11) NOT NULL,
  `saldoawal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `debet` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kredit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `memorial_debet` decimal(15,2) NOT NULL DEFAULT '0.00',
  `penyesuaian_debet` decimal(15,2) NOT NULL DEFAULT '0.00',
  `penyesuaian_kredit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `memorial_kredit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `saldoakhirk` decimal(15,2) DEFAULT '0.00',
  `periode` varchar(6) NOT NULL,
  `saldoakhird` decimal(15,2) DEFAULT '0.00',
  KEY `nomor` (`nomor`),
  CONSTRAINT `perkiraan_saldo_ibfk_1` FOREIGN KEY (`nomor`) REFERENCES `perkiraan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `perkiraan_saldo` */

insert  into `perkiraan_saldo`(`nomor`,`saldoawal`,`debet`,`kredit`,`memorial_debet`,`penyesuaian_debet`,`penyesuaian_kredit`,`memorial_kredit`,`saldoakhirk`,`periode`,`saldoakhird`) values (53,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'202208',0.00),(61,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'202208',0.00),(77,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'202208',0.00),(96,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'202208',0.00),(609,342350.00,0.00,0.00,0.00,0.00,0.00,0.00,342350.00,'202208',342350.00),(3528,342350.00,0.00,0.00,0.00,0.00,0.00,0.00,342350.00,'202208',342350.00),(3528,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'202207',0.00),(53,0.00,0.00,500000.00,0.00,0.00,0.00,0.00,500000.00,'202207',-500000.00),(609,0.00,500000.00,157650.00,0.00,0.00,0.00,0.00,-342350.00,'202207',342350.00),(61,0.00,7650.00,0.00,0.00,0.00,0.00,0.00,-7650.00,'202207',7650.00),(77,0.00,150000.00,0.00,0.00,0.00,0.00,0.00,-150000.00,'202207',150000.00),(96,0.00,0.00,342350.00,0.00,0.00,0.00,0.00,342350.00,'202207',-342350.00),(53,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'202209',0.00),(61,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'202209',0.00),(77,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'202209',0.00),(96,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'202209',0.00),(609,342350.00,0.00,0.00,0.00,0.00,0.00,0.00,342350.00,'202209',342350.00),(3528,342350.00,0.00,0.00,0.00,0.00,0.00,0.00,342350.00,'202209',342350.00);

/*Table structure for table `perkiraan_temp` */

DROP TABLE IF EXISTS `perkiraan_temp`;

CREATE TABLE `perkiraan_temp` (
  `nomor` varchar(9) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `levelno` int(11) DEFAULT NULL,
  `nomorheader` varchar(9) DEFAULT NULL,
  `posisidk` varchar(6) DEFAULT NULL,
  `posisinr` varchar(6) DEFAULT NULL,
  `jenisjurnal` varchar(10) DEFAULT NULL,
  `posisineraca` varchar(10) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `jenislevel` varchar(10) DEFAULT NULL,
  `link` varchar(15) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor` (`nomor`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;

/*Data for the table `perkiraan_temp` */

insert  into `perkiraan_temp`(`nomor`,`nama`,`levelno`,`nomorheader`,`posisidk`,`posisinr`,`jenisjurnal`,`posisineraca`,`keterangan`,`jenislevel`,`link`,`id`,`userid`) values ('1','A K T I V A',1,'','DEBET','NERACA','','DEBET','aktiva','JUDUL',NULL,1,NULL),('11000','AKTIVA LANCAR',2,'1','DEBET','NERACA','','DEBET','','JUDUL',NULL,2,NULL),('11100','KAS DAN BANK',3,'11000','DEBET','NERACA','','DEBET','','JUDUL',NULL,3,NULL),('11101','KAS',4,'11100','DEBET','NERACA','','DEBET','','DETIL',NULL,4,NULL),('11200','BANK',3,'11000','DEBET','NERACA','','DEBET','','JUDUL',NULL,5,NULL),('11201','BANK',4,'11200','DEBET','NERACA','','DEBET','','DETIL',NULL,6,NULL),('11300','SIMPANAN BERHARGA',3,'11000','DEBET','NERACA','','DEBET','','JUDUL',NULL,7,NULL),('11301','SIMPANAN BERHARGA',4,'11300','DEBET','NERACA','','DEBET','','DETIL',NULL,8,NULL),('11400','PIUTANG DAGANG',3,'11000','DEBET','NERACA','','DEBET','','JUDUL',NULL,9,NULL),('11401','PIUTANG DAGANG',4,'11400','DEBET','NERACA','','DEBET','','DETIL',NULL,10,NULL),('11500','PIUTANG KARYAWAN',3,'11000','DEBET','NERACA','','DEBET','','JUDUL',NULL,11,NULL),('11501','PIUTANG KARYAWAN',4,'11500','DEBET','NERACA','','DEBET','','DETIL',NULL,12,NULL),('11600','PIUTANG LAIN-LAIN',3,'11000','DEBET','NERACA','','DEBET','','JUDUL',NULL,13,NULL),('11601','PIUTANG LAIN-LAIN',4,'11600','DEBET','NERACA','','DEBET','','DETIL',NULL,14,NULL),('11900','STOCK BARANG',3,'11000','DEBET','NERACA','','DEBET','','JUDUL',NULL,15,NULL),('11901','PERSEDIAAN BARANG DAGANGAN',4,'11900','DEBET','NERACA','','DEBET','','DETIL',NULL,16,NULL),('12000','AKTIVA TETAP',2,'1','DEBET','NERACA','','DEBET','','JUDUL',NULL,17,NULL),('12100','TANAH DAN BANGUNAN',3,'12000','DEBET','NERACA','','DEBET','','JUDUL',NULL,18,NULL),('12101','TANAH DAN BANGUNAN',4,'12100','DEBET','NERACA','','DEBET','','DETIL',NULL,19,NULL),('12200','AKUMULASI TANAH & BANGUNAN',3,'12000','DEBET','NERACA','','DEBET','','JUDUL',NULL,20,NULL),('12201','AKUMULASI TANAH DAN BANGUNAN',4,'12200','DEBET','NERACA','','DEBET','','DETIL',NULL,21,NULL),('12300','KENDARAAN BERMOTOR',3,'12000','DEBET','NERACA','','DEBET','','JUDUL',NULL,22,NULL),('12301','KENDARAAN BERMOTOR',4,'12300','DEBET','NERACA','','DEBET','','DETIL',NULL,23,NULL),('12400','AKUMULASI PH KENDARAAN MOTOR',3,'12000','DEBET','NERACA','','DEBET','','JUDUL',NULL,24,NULL),('12401','AKUMULASI KENDARAAN BERMOTOR',4,'12400','DEBET','NERACA','','DEBET','','DETIL',NULL,25,NULL),('12500','INVENTARIS KANTOR',3,'12000','DEBET','NERACA','','DEBET','','JUDUL',NULL,26,NULL),('12501','INVENTARIS KANTOR',4,'12500','DEBET','NERACA','','DEBET','','DETIL',NULL,27,NULL),('12600','AKUMULASI PH INVENTARIS KANTOR',3,'12000','DEBET','NERACA','','DEBET','','JUDUL',NULL,28,NULL),('12601','AKUMULASI INVENTARIS KANTOR',4,'12600','DEBET','NERACA','','DEBET','','DETIL',NULL,29,NULL),('13000','AKTIVA LAIN-LAIN',2,'1','DEBET','NERACA','','DEBET','','JUDUL',NULL,30,NULL),('13100','PRIVE PERSERO',3,'13000','DEBET','NERACA','','DEBET','','JUDUL',NULL,31,NULL),('13101','PRIVE PERSERO',4,'13100','DEBET','NERACA','','DEBET','','DETIL',NULL,32,NULL),('13200','PAJAK DIBAYAR DIMUKA',3,'13000','DEBET','NERACA','','DEBET','','JUDUL',NULL,33,NULL),('13201','PAJAK DIBAYAR DIMUKA',4,'13200','DEBET','NERACA','','DEBET','','DETIL',NULL,34,NULL),('2','PASSIVA',1,'','KREDIT','NERACA','','KREDIT','','JUDUL',NULL,35,NULL),('21000','PASIVA LANCAR',2,'2','KREDIT','NERACA','','KREDIT','','JUDUL',NULL,36,NULL),('21100','HUTANG BANK',3,'21000','KREDIT','NERACA','','KREDIT','','JUDUL',NULL,37,NULL),('21101','HUTANG BANK',4,'21100','KREDIT','NERACA','','KREDIT','','DETIL',NULL,38,NULL),('21200','HUTANG DAGANG',3,'21000','KREDIT','NERACA','','KREDIT','','JUDUL',NULL,39,NULL),('21201','HUTANG DAGANG',4,'21200','KREDIT','NERACA','','KREDIT','','DETIL',NULL,40,NULL),('21300','HUTANG LAIN-LAIN',3,'21000','KREDIT','NERACA','','KREDIT','','JUDUL',NULL,41,NULL),('21301','HUTANG LAIN-LAIN',4,'21300','KREDIT','NERACA','','KREDIT','','DETIL',NULL,42,NULL),('21400','HUTANG PAJAK',3,'21000','KREDIT','NERACA','','KREDIT','','JUDUL',NULL,43,NULL),('21401','HUTANG PAJAK',4,'21400','KREDIT','NERACA','','KREDIT','','DETIL',NULL,44,NULL),('23000','MODAL SENDIRI',2,'2','KREDIT','NERACA','','KREDIT','','JUDUL',NULL,45,NULL),('23100','M O D A L',3,'23000','KREDIT','NERACA','','KREDIT','','JUDUL',NULL,46,NULL),('23101','MODAL DISETOR',4,'23100','KREDIT','NERACA','','KREDIT','','DETIL',NULL,47,NULL),('31105','RETUR PEMBELIAN',4,'31100','KREDIT','R/L',NULL,'KREDIT',NULL,'DETIL',NULL,48,NULL),('31106','PEND RETUR PEMBELIAN',4,'31100','KREDIT','R/L',NULL,'KREDIT',NULL,'DETIL',NULL,49,NULL),('3','PENDAPATAN DAN HARGA POKOK',1,'','KREDIT','R/L','','KREDIT','','JUDUL',NULL,50,NULL),('31000','PENDAPATAN',2,'3','KREDIT','R/L','','KREDIT','','JUDUL',NULL,51,NULL),('31100','PENJUALAN BARANG',3,'31000','KREDIT','R/L','','KREDIT','','JUDUL',NULL,52,NULL),('31101','PENJUALAN BARANG DAGANGAN',4,'31100','KREDIT','R/L','','KREDIT','','DETIL',NULL,53,NULL),('31102','DISCOUNT PENJUALAN',4,'31100','KREDIT','R/L','','KREDIT','','DETIL',NULL,54,NULL),('31103','RETUR PENJUALAN',4,'31100','KREDIT','R/L','','KREDIT','','DETIL',NULL,55,NULL),('31104','HARGA POKOK PENJUALAN',4,'31100','DEBET','R/L','','DEBET','','DETIL',NULL,56,NULL),('4','BIAYA-BIAYA',1,'','DEBET','R/L','','DEBET','','JUDUL',NULL,57,NULL),('41000','BIAYA PENJUALAN',2,'4','DEBET','R/L','','DEBET','','JUDUL',NULL,58,NULL),('41100','BIAYA PENJUALAN',3,'41000','DEBET','R/L','','DEBET','','JUDUL',NULL,59,NULL),('41101','BIAYA PENGIRIMAN+SEWA ANGKUTAN',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,60,NULL),('41102','BENSIN/SOLAR',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,61,NULL),('41103','SERVIS MOBIL/KENDARAAN',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,62,NULL),('41104','PERPANJANGAN STNK + SIM',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,63,NULL),('41105','BIAYA PENGINAPAN/LUAR KOTA',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,64,NULL),('41106','BIAYA LEMBUR',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,65,NULL),('41107','UANG MAKAN',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,66,NULL),('41108','NGEMEL',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,67,NULL),('31107','BIAYA RETUR PEMBELIAN',4,'31100','DEBET','R/L',NULL,'DEBET',NULL,'DETIL',NULL,68,NULL),('41109','KOMISI',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,69,NULL),('41110','BIAYA ADMINISTRASI BANK',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,70,NULL),('41111','BIAYA SEWA',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,71,NULL),('41112','RETRIBUSI',4,'41100','DEBET','R/L','','DEBET','','DETIL',NULL,72,NULL),('42000','BIAYA ADMINISTRASI DAN UMUM',2,'4','DEBET','R/L','','DEBET','','JUDUL',NULL,73,NULL),('42100','BIAYA ADMINISTRASI DAN UMUM',3,'42000','DEBET','R/L','','DEBET','','JUDUL',NULL,74,NULL),('42101','GAJI',4,'42100','DEBET','R/L','','KREDIT','','DETIL',NULL,75,NULL),('42102','KEPERLUAN KANTOR',4,'42100','DEBET','R/L','','KREDIT','','DETIL',NULL,76,NULL),('42103','TELEPON/LISTRIK/AIR',4,'42100','DEBET','R/L','','KREDIT','','DETIL',NULL,77,NULL),('42104','SERVIS PERALATAN',4,'42100','DEBET','R/L','','KREDIT','','DETIL',NULL,78,NULL),('42105','PENGURUSAN NPWP+PEMBUATAN AKTA',4,'42100','DEBET','R/L','','DEBET','','DETIL',NULL,79,NULL),('42106','BONUS',4,'42100','DEBET','R/L','','DEBET','','DETIL',NULL,80,NULL),('43000','BIAYA LAIN-LAIN',2,'4','DEBET','R/L','','DEBET','','JUDUL',NULL,81,NULL),('43100','BIAYA LAIN-LAIN',3,'43000','DEBET','R/L','','DEBET','','JUDUL',NULL,82,NULL),('43101','BIAYA LAIN-LAIN',4,'43100','DEBET','R/L','','DEBET','','DETIL',NULL,83,NULL),('5','PENDAPATAN DAN BIAYA LAIN-LAIN',1,'','KREDIT','R/L','','KREDIT','','JUDUL',NULL,84,NULL),('51000','PENDAPATAN LAIN-LAIN',2,'5','KREDIT','R/L','','KREDIT','','JUDUL',NULL,85,NULL),('51100','PENDAPATAN LAIN-LAIN',3,'51000','KREDIT','R/L','','KREDIT','','JUDUL',NULL,86,NULL),('51101','PENDAPATAN LAIN-LAIN',4,'51100','KREDIT','R/L','','KREDIT','','DETIL',NULL,87,NULL),('52000','PENDAPATAN PENJUALAN',2,'5','KREDIT','R/L','','KREDIT','','JUDUL',NULL,88,NULL),('52100','PENDAPATAN PENJUALAN',3,'52000','KREDIT','R/L','','KREDIT','','JUDUL',NULL,89,NULL),('52101','PENDAPATAN PENJUALAN',4,'52100','KREDIT','R/L','','KREDIT','','DETIL',NULL,90,NULL),('11402','PIUTANG BG',4,'11400','DEBET','NERACA',NULL,'DEBET',NULL,'DETIL',NULL,91,NULL),('21202','HUTANG BG',4,'21200','KREDIT','NERACA',NULL,'KREDIT',NULL,'DETIL',NULL,92,NULL),('11403','PIUTANG TITIPAN SUPLIER',4,'11400','DEBET','NERACA',NULL,'DEBET',NULL,'DETIL',NULL,93,NULL),('21203','PIUTANG TITIPAN CUSTOMER',4,'21200','KREDIT','NERACA',NULL,'KREDIT',NULL,'DETIL',NULL,94,NULL),('23102','LABA DITAHAN',4,'23100','KREDIT','NERACA',NULL,'KREDIT',NULL,'DETIL',NULL,95,NULL),('23103','LABA PERIODE BERJALAN',4,'23100','KREDIT','NERACA',NULL,'KREDIT',NULL,'DETIL',NULL,96,NULL),('42107','BIAYA PENYUSUTAN AKTIVA',4,'42100','DEBET','R/L',NULL,'DEBET',NULL,'DETIL',NULL,97,NULL),('43102','STOK OPNAME FISIK KURANG',4,'43100','DEBET','R/L',NULL,'DEBET',NULL,'DETIL',NULL,98,NULL),('51102','STOK OPNAME FISIK LEBIH',4,'51100','KREDIT','R/L',NULL,'KREDIT',NULL,'DETIL',NULL,99,NULL),('41113','BIAYA PEMAKAIAN',4,'41100','DEBET','R/L',NULL,'DEBET','PEMAKAIAN','DETIL',NULL,100,NULL),('31108','PENDAPATAN AMAL',4,'31100','KREDIT','R/L',NULL,'KREDIT','','DETIL',NULL,101,NULL);

/*Table structure for table `pos` */

DROP TABLE IF EXISTS `pos`;

CREATE TABLE `pos` (
  `jualtunai` int(11) DEFAULT '0',
  `jualkredit` int(11) DEFAULT '0',
  `piutangdagang` int(11) DEFAULT '0',
  `hutangdagang` int(11) DEFAULT '0',
  `returjual` int(11) DEFAULT '0',
  `returbeli` int(11) DEFAULT '0',
  `persediaan` int(11) DEFAULT '0',
  `hpp` int(11) DEFAULT '0',
  `ppnmasukan` int(11) DEFAULT '0',
  `ppnkeluaran` int(11) DEFAULT '0',
  `labatahunlalu` int(11) DEFAULT '0',
  `labatahunberjalan` int(11) DEFAULT '0',
  `lababulanberjalan` int(11) DEFAULT '0',
  `pembelian` int(11) DEFAULT '0',
  `beli_kas` int(11) DEFAULT '0',
  `beli_discount` int(11) DEFAULT '0',
  `jual_kas` int(11) DEFAULT '0',
  `jual_discount` int(11) DEFAULT '0',
  `rbeli_laba` int(11) DEFAULT '0',
  `rbeli_rugi` int(11) DEFAULT '0',
  `opname_fisik_kurang` int(11) DEFAULT '0',
  `opname_fisik_lebih` int(11) DEFAULT '0',
  `persediaankonsinyasi` int(11) DEFAULT '0',
  `bebanbeli` int(11) DEFAULT '0',
  `pemakaian` int(11) DEFAULT '0',
  `jual_lain` int(11) DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `hapus` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  CONSTRAINT `pos_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `pos` */

insert  into `pos`(`jualtunai`,`jualkredit`,`piutangdagang`,`hutangdagang`,`returjual`,`returbeli`,`persediaan`,`hpp`,`ppnmasukan`,`ppnkeluaran`,`labatahunlalu`,`labatahunberjalan`,`lababulanberjalan`,`pembelian`,`beli_kas`,`beli_discount`,`jual_kas`,`jual_discount`,`rbeli_laba`,`rbeli_rugi`,`opname_fisik_kurang`,`opname_fisik_lebih`,`persediaankonsinyasi`,`bebanbeli`,`pemakaian`,`jual_lain`,`id`,`userid`,`hapus`) values (53,53,10,40,55,16,16,56,34,44,45,3528,96,16,4,87,4,54,49,68,98,1499,0,0,100,4,10,NULL,0);

/*Table structure for table `pos_temp` */

DROP TABLE IF EXISTS `pos_temp`;

CREATE TABLE `pos_temp` (
  `jualtunai` varchar(9) DEFAULT NULL,
  `jualkredit` varchar(9) DEFAULT NULL,
  `piutangdagang` varchar(9) DEFAULT NULL,
  `hutangdagang` varchar(9) DEFAULT NULL,
  `returjual` varchar(9) DEFAULT NULL,
  `returbeli` varchar(9) DEFAULT NULL,
  `persediaan` varchar(9) DEFAULT NULL,
  `hpp` varchar(9) DEFAULT NULL,
  `ppnmasukan` varchar(9) DEFAULT NULL,
  `ppnkeluaran` varchar(9) DEFAULT NULL,
  `labatahunlalu` varchar(9) DEFAULT NULL,
  `labatahunberjalan` varchar(9) DEFAULT NULL,
  `lababulanberjalan` varchar(9) DEFAULT NULL,
  `pembelian` varchar(9) DEFAULT NULL,
  `beli_kas` varchar(9) DEFAULT NULL,
  `beli_discount` varchar(9) DEFAULT NULL,
  `jual_kas` varchar(9) DEFAULT NULL,
  `jual_discount` varchar(9) DEFAULT NULL,
  `rbeli_laba` varchar(9) DEFAULT NULL,
  `rbeli_rugi` varchar(9) DEFAULT NULL,
  `opname_fisik_kurang` varchar(9) DEFAULT NULL,
  `opname_fisik_lebih` varchar(9) DEFAULT NULL,
  `persediaankonsinyasi` varchar(9) DEFAULT NULL,
  `bebanbeli` varchar(9) DEFAULT NULL,
  `pemakaian` varchar(9) DEFAULT NULL,
  `jual_lain` varchar(9) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `pos_temp` */

insert  into `pos_temp`(`jualtunai`,`jualkredit`,`piutangdagang`,`hutangdagang`,`returjual`,`returbeli`,`persediaan`,`hpp`,`ppnmasukan`,`ppnkeluaran`,`labatahunlalu`,`labatahunberjalan`,`lababulanberjalan`,`pembelian`,`beli_kas`,`beli_discount`,`jual_kas`,`jual_discount`,`rbeli_laba`,`rbeli_rugi`,`opname_fisik_kurang`,`opname_fisik_lebih`,`persediaankonsinyasi`,`bebanbeli`,`pemakaian`,`jual_lain`,`id`) values ('31101','31101','11401','21201','31103','11901','11901','31104','13201','21401','23000','23102','23103','11901','11101','51101','11101','31102','31106','31107','43102','51102','0','43101','41113','51101',1);

/*Table structure for table `setting` */

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `kota` varchar(200) DEFAULT NULL,
  `provinsi` varchar(200) DEFAULT NULL,
  `telp` varchar(50) DEFAULT NULL,
  `periode` date DEFAULT NULL,
  `foto` varchar(200) DEFAULT NULL,
  `et` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `setting` */

insert  into `setting`(`id`,`nama`,`alamat`,`kota`,`provinsi`,`telp`,`periode`,`foto`,`et`) values (1,'KAS-KU','Jl. Baru','Semarang','Jawa Tengah','081567605999','2019-04-30','','2021-04-20 12:55:06');

/*Table structure for table `status` */

DROP TABLE IF EXISTS `status`;

CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` int(11) NOT NULL DEFAULT '0',
  `nama` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `status` */

insert  into `status`(`id`,`kode`,`nama`) values (1,0,'MENUNGGU'),(2,1,'DI PROSES SEBAGIAN'),(3,2,'DI PROSES PENUH');

/*Table structure for table `temp` */

DROP TABLE IF EXISTS `temp`;

CREATE TABLE `temp` (
  `id` int(11) DEFAULT NULL,
  `ket` varchar(200) DEFAULT NULL,
  `nilai` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `temp` */

insert  into `temp`(`id`,`ket`,`nilai`) values (1,NULL,NULL);

/*Table structure for table `trankask_d` */

DROP TABLE IF EXISTS `trankask_d`;

CREATE TABLE `trankask_d` (
  `idh` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `norek` int(11) DEFAULT NULL,
  `debet` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kredit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` varchar(100) DEFAULT NULL,
  `reposting` int(11) DEFAULT '0',
  `it` datetime DEFAULT NULL,
  `et` datetime DEFAULT NULL,
  `dt` datetime DEFAULT NULL,
  `hapus` int(11) DEFAULT '0',
  `userid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idh` (`idh`),
  KEY `norek` (`norek`),
  CONSTRAINT `trankask_d_ibfk_1` FOREIGN KEY (`idh`) REFERENCES `trankask_h` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `trankask_d_ibfk_2` FOREIGN KEY (`norek`) REFERENCES `perkiraan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `trankask_d` */

insert  into `trankask_d`(`idh`,`id`,`norek`,`debet`,`kredit`,`keterangan`,`reposting`,`it`,`et`,`dt`,`hapus`,`userid`) values (5,9,61,7650.00,0.00,'BENSIN 1 LITER',0,'2022-07-16 19:54:11',NULL,NULL,0,4),(6,10,77,150000.00,0.00,'bayar listrik',0,'2022-07-16 20:10:38',NULL,NULL,0,4);

/*Table structure for table `trankask_h` */

DROP TABLE IF EXISTS `trankask_h`;

CREATE TABLE `trankask_h` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nobukti` varchar(25) NOT NULL,
  `kodebank` int(11) NOT NULL,
  `tgl` date DEFAULT NULL,
  `totaldebet` decimal(15,2) DEFAULT '0.00',
  `totalkredit` decimal(15,2) DEFAULT '0.00',
  `userid` varchar(200) NOT NULL,
  `periode` varchar(6) DEFAULT NULL,
  `deskripsi` varchar(200) DEFAULT NULL,
  `jenis` varchar(6) DEFAULT 'KELUAR',
  `hapus` int(11) DEFAULT '0',
  `it` datetime DEFAULT NULL,
  `et` datetime DEFAULT NULL,
  `dt` datetime DEFAULT NULL,
  `dperiode` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nobukti` (`nobukti`),
  KEY `kodebank` (`kodebank`),
  CONSTRAINT `trankask_h_ibfk_2` FOREIGN KEY (`kodebank`) REFERENCES `bank` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `trankask_h` */

insert  into `trankask_h`(`id`,`nobukti`,`kodebank`,`tgl`,`totaldebet`,`totalkredit`,`userid`,`periode`,`deskripsi`,`jenis`,`hapus`,`it`,`et`,`dt`,`dperiode`) values (5,'2022071600000001',60,'2022-07-16',7650.00,0.00,'4','202207','','KELUAR',0,'2022-07-16 19:54:11',NULL,NULL,20220716),(6,'2022071600000002',60,'2022-07-16',150000.00,0.00,'4','202207','','KELUAR',0,'2022-07-16 20:10:38',NULL,NULL,20220716);

/*Table structure for table `trankasm_d` */

DROP TABLE IF EXISTS `trankasm_d`;

CREATE TABLE `trankasm_d` (
  `idh` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `norek` int(11) DEFAULT NULL,
  `debet` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kredit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` varchar(100) DEFAULT NULL,
  `reposting` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `hapus` int(11) DEFAULT '0',
  `dt` datetime DEFAULT NULL,
  `it` datetime DEFAULT NULL,
  `et` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idh` (`idh`),
  KEY `norek` (`norek`),
  CONSTRAINT `trankasm_d_ibfk_1` FOREIGN KEY (`idh`) REFERENCES `trankasm_h` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `trankasm_d_ibfk_3` FOREIGN KEY (`norek`) REFERENCES `perkiraan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `trankasm_d` */

insert  into `trankasm_d`(`idh`,`id`,`norek`,`debet`,`kredit`,`keterangan`,`reposting`,`userid`,`hapus`,`dt`,`it`,`et`) values (3,7,53,0.00,500000.00,'JASA SERVIS AC 5 UNIT',NULL,4,0,NULL,'2022-07-16 19:53:00',NULL);

/*Table structure for table `trankasm_h` */

DROP TABLE IF EXISTS `trankasm_h`;

CREATE TABLE `trankasm_h` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nobukti` varchar(25) NOT NULL,
  `kodebank` int(11) NOT NULL,
  `tgl` date DEFAULT NULL,
  `totaldebet` decimal(15,2) DEFAULT '0.00',
  `totalkredit` decimal(15,2) DEFAULT '0.00',
  `userid` int(11) NOT NULL,
  `periode` varchar(6) DEFAULT NULL,
  `deskripsi` varchar(200) DEFAULT NULL,
  `jenis` varchar(6) DEFAULT 'MASUK',
  `hapus` int(11) DEFAULT '0',
  `dt` datetime DEFAULT NULL,
  `dperiode` int(11) DEFAULT NULL,
  `it` datetime DEFAULT NULL,
  `et` datetime DEFAULT NULL,
  `reposting` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nobukti` (`nobukti`),
  KEY `kodebank` (`kodebank`),
  CONSTRAINT `trankasm_h_ibfk_1` FOREIGN KEY (`kodebank`) REFERENCES `bank` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `trankasm_h` */

insert  into `trankasm_h`(`id`,`nobukti`,`kodebank`,`tgl`,`totaldebet`,`totalkredit`,`userid`,`periode`,`deskripsi`,`jenis`,`hapus`,`dt`,`dperiode`,`it`,`et`,`reposting`) values (3,'2022071600000001',60,'2022-07-16',0.00,500000.00,4,'202207','','MASUK',0,NULL,20220716,'2022-07-16 19:53:00',NULL,0);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `nickname` varchar(200) DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `it` datetime DEFAULT NULL,
  `et` datetime DEFAULT NULL,
  `dt` datetime DEFAULT NULL,
  `hapus` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`id`,`nama`,`nickname`,`password`,`it`,`et`,`dt`,`hapus`) values (1,'akbar','akbar','akbar',NULL,'2021-08-27 15:26:47','2022-07-13 09:20:28',0),(2,'budi','budi','budi','2022-07-13 09:04:25','2022-07-14 16:15:47','2022-07-13 09:23:02',0),(3,'budie','budie','budie','2022-07-13 09:08:42',NULL,'2022-07-13 09:21:03',0),(4,'agus','agus','agus','2022-07-13 09:11:44',NULL,'2022-07-13 09:21:08',0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
