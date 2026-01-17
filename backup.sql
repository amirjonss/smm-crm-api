/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: kh-agency-api
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `content_plan`
--

DROP TABLE IF EXISTS `content_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `content_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `created_by_id` int(11) NOT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `deleted_by_id` int(11) DEFAULT NULL,
  `post` varchar(255) NOT NULL,
  `format` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `idea` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1038B6B8166D1F9C` (`project_id`),
  KEY `IDX_1038B6B8B03A8386` (`created_by_id`),
  KEY `IDX_1038B6B8896DBBDE` (`updated_by_id`),
  KEY `IDX_1038B6B8C76F1F52` (`deleted_by_id`),
  CONSTRAINT `FK_1038B6B8166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_1038B6B8896DBBDE` FOREIGN KEY (`updated_by_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_1038B6B8B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_1038B6B8C76F1F52` FOREIGN KEY (`deleted_by_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=359 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_plan`
--

LOCK TABLES `content_plan` WRITE;
/*!40000 ALTER TABLE `content_plan` DISABLE KEYS */;
INSERT INTO `content_plan` VALUES
(3,3,4,NULL,NULL,'Test1','Reels','2025-11-05','sadsfsadf','2025-11-10 01:23:24',NULL),
(4,4,5,NULL,NULL,'Biz ochildik!','Post','2025-12-12','ggg','2025-11-14 14:10:03',NULL),
(5,6,5,NULL,NULL,'ddd','Reels','2025-11-13','ddd','2025-11-14 14:13:41',NULL),
(6,7,6,NULL,NULL,'Nextform mebellar — xonadoningiz ko‘rki!','Carousel','2025-11-14','Nextform mebellar — xonadoningiz ko‘rki! Fotopost','2025-11-15 06:36:25',NULL),
(7,7,6,6,NULL,'Qulaylik uchun mukammal tanlov!','Reels','2025-11-17','Syujetli video','2025-11-15 06:37:43','2025-11-15 06:38:04'),
(8,7,6,NULL,NULL,'Eng yaxshisi bizda!','Reels','2025-11-20','Syujetli video','2025-11-15 06:38:45',NULL),
(9,7,6,NULL,NULL,'Qashqirlar istagidagi mebellar! ','Reels','2025-11-23','Ovozsiz trend video','2025-11-15 06:39:24',NULL),
(10,7,6,NULL,NULL,'\"Enzo\" stol-stullari!','Reels','2025-11-26','obzor','2025-11-15 06:39:53',NULL),
(11,7,6,NULL,NULL,'Ish jarayonlari!','Reels','2024-11-29','Mebelning 0 dan tayyorlanishi','2025-11-15 06:41:40',NULL),
(12,7,6,NULL,NULL,'\"Time\"  stol-stullari oshxonangiz dizaynini ideal darajada ochib beradi','Reels','2025-12-01','obzor','2025-11-15 06:42:29',NULL),
(13,7,6,NULL,NULL,'Tanlov','Reels','2025-12-04','Syujetli video','2025-11-15 06:43:45',NULL),
(14,7,6,NULL,NULL,'Bir stul – bir esdalik','Reels','2025-12-07','Syujetli video','2025-11-15 06:45:36',NULL),
(15,7,6,NULL,NULL,'Bejirim mebellar','Carousel','2025-12-10','Fotopost','2025-11-15 06:46:09',NULL),
(16,7,6,NULL,NULL,'Yotoqxona mebellari','Reels','2025-12-12','Maxsus turdagi mebellar obzori','2025-11-15 06:47:55',NULL),
(17,7,6,NULL,NULL,'Fabrikada 1 kun!','Reels','2025-12-11','Fabrikadagi jarayonlar yoritiladi','2025-11-15 06:49:05',NULL),
(18,8,6,NULL,NULL,'Pulingiz uy olishga yetmayaptimi?','Reels','2025-11-12','obzor','2025-11-15 07:08:04',NULL),
(19,8,6,6,NULL,'Katlavan qachon boshlanadi?','Reels','2025-11-14','obzor','2025-11-15 07:09:13','2025-11-15 07:14:36'),
(20,8,6,6,NULL,'Yangi hayot boshlanmoqda','Reels','2025-11-17','obzor','2025-11-15 07:11:13','2025-11-15 07:14:43'),
(21,8,6,6,NULL,'8 000 oila uchun yangi shahar','Reels','2025-11-20','obzor','2025-11-15 07:12:57','2025-11-15 07:14:49'),
(22,8,6,6,NULL,'Sokin hayot — shahar ichida, shovqindan yiroq','Reels','2025-11-23','obzor','2025-11-15 07:13:21','2025-11-15 07:14:55'),
(23,8,6,NULL,NULL,'Oilangiz uchun yaratilgan','Reels','2025-11-26','obzor','2025-11-15 07:14:23',NULL),
(24,8,6,NULL,NULL,'Bugun qaror qabul qiling — ertangi orzular shu yerda!','Reels','2025-11-29','obzor','2025-11-15 07:15:36',NULL),
(25,8,6,NULL,NULL,'Memar Siz uchun mo\'ljallangan','Reels','2025-12-01','obzor','2025-11-15 07:16:30',NULL),
(26,8,6,NULL,NULL,'Memar / Grafik post','Post','2025-12-03','obzor','2025-11-15 07:17:56',NULL),
(27,8,6,NULL,NULL,'Memar / Grafik post','Post','2025-12-05','obzor','2025-11-15 07:19:34',NULL),
(28,8,6,NULL,NULL,'Xaridorlar bilan muloqot','Reels','2025-12-08','obzor','2025-11-15 07:20:22',NULL),
(29,8,6,NULL,NULL,'Sizga qanday uy muhim? / Ko\'chada savol-javob','Reels','2025-12-12','interaktiv vlog','2025-11-15 07:21:07',NULL),
(30,9,9,NULL,NULL,'Qurilishi tugallangan uylar','Reels','2025-11-17','2025-yilda qurilishi tugatilishi rejalashtirilgan uylar haqida ma’lumot','2025-11-15 14:39:19',NULL),
(31,10,5,NULL,NULL,'BIZ OCHILDIK!','Post','2025-11-18','KIRISH POSTI','2025-11-17 06:50:10',NULL),
(32,10,5,5,NULL,'DSDFDSF DFDSF','Animation','2025-11-20','CDCSDCDS','2025-11-17 06:51:16','2025-11-17 06:51:26'),
(33,10,5,5,NULL,'EDFUIIII','Reels','2025-11-28','DFDSFDSF','2025-11-17 06:52:25','2025-11-17 06:52:54'),
(34,11,10,NULL,NULL,'Post_1','Animation','2025-11-19','Idea_1','2025-11-17 07:14:15',NULL),
(35,12,7,NULL,NULL,'Stifatli plita tanlashda nimalarga etibor berish kerak?','Carousel','2025-11-12','Sifatli plita tanlash haqida foydali post!','2025-11-18 06:37:33',NULL),
(36,12,7,NULL,NULL,'STB beton har qanday sharoitda ham yetkazib beramiz!','Animation','2025-11-15','Tez va sifatli yetkazib berish!','2025-11-18 06:38:38',NULL),
(37,12,7,NULL,NULL,'Sifatli gazablok kelajak ishonchi!','Carousel','2025-11-18','Gazablokni to\'g\'ri tanlsh haqida!','2025-11-18 06:40:33',NULL),
(38,12,7,NULL,NULL,'Sifat bizda birinchi o\'rinda!','Reels','2025-11-21','Ish jaray onidan video lavhalar!','2025-11-18 06:48:26',NULL),
(39,12,7,NULL,NULL,'Yana bir mijozimizni ishonchini oqladik!','Reels','2025-11-24','Qurilishdan lavhalar!','2025-11-18 07:14:27',NULL),
(40,12,7,NULL,NULL,'Gazablokmi yaxshi, yoki shlakablokmi?','Reels','2025-11-27','Sifat va qulaylikni ko\'rsatish!','2025-11-18 10:14:54',NULL),
(41,12,7,NULL,NULL,'Trend video Lego','Animation','2025-11-30','Suniy intelekt video','2025-11-18 10:16:43',NULL),
(42,12,7,NULL,NULL,'Manshuni ko\'paytiraolasnmi?','Reels','2025-12-03','Abdullajon filmidan parcha!','2025-11-18 10:40:22',NULL),
(43,12,7,NULL,NULL,'Biz bilan vaqtingizni va pulingizni tejang!','Reels','2025-12-06','Bizni tanlash uchun maqsadli video','2025-11-18 11:53:57',NULL),
(44,12,7,NULL,NULL,'Siz izlagan barcha turdagi plitlar aynan bizda!','Carousel','2025-12-09','Karusel post plita tklifi','2025-11-18 11:56:16',NULL),
(50,10,5,NULL,NULL,'ss','Reels','2025-11-25','94246ce346bbe6fb3db96c8176e76268a8642be5842ee76ba6b762253504c385','2025-11-24 15:18:39',NULL),
(51,10,5,NULL,NULL,'asa','Reels','2025-11-28','https://www.pinterest.com/pin/382313455890117058/','2025-11-24 15:19:29',NULL),
(63,15,12,12,NULL,'“Ayollar nega tilla bergan erkakni boshqacha qadrlaydi?”','Reels','2025-11-26','Psixologik ma`lumot beruvchi ','2025-11-25 06:36:35','2025-11-25 08:00:27'),
(64,15,12,12,NULL,'“Qaysi yoshda qaysi tilla chiroyli turadi?”','Reels','2025-11-29','Yoshga mos tilla tanlsh ga maslahat','2025-11-25 06:40:35','2025-11-25 08:00:40'),
(65,15,12,12,NULL,' “Ayolning qaysi barmog‘iga taqilgan uzuk nimani bildiradi?”','Reels','2025-12-01','Informatsion reels','2025-11-25 06:42:04','2025-11-25 08:00:52'),
(66,15,12,12,NULL,'Kimga Qanday rangdagi tilla yarashadi','Reels','2025-12-04','Teri rangiga qarab tlla turini tanlash ','2025-11-25 06:46:03','2025-11-25 08:01:02'),
(67,15,12,12,NULL,'“2025 moda: ayollar nega minimalist taqinchoqni tanlayapti?”','Reels','2025-12-07','Minimalistik taqinchoqlar haqida','2025-11-25 06:49:57','2025-11-25 08:01:13'),
(68,15,12,12,NULL,'“Ayol har kuni taqib yura oladigan tilla — qanday bo‘ladi?”','Reels','2025-12-10','Ma`lumot beruvchi ','2025-11-25 06:50:45','2025-11-25 08:01:32'),
(69,15,12,12,NULL,'“Tilla sovg‘a qilib, munosabatni mustahkamlasa bo‘ladimi?” (psixologik)','Reels','2025-12-13','Psixologik munosabatlarga oid','2025-11-25 06:52:13','2025-11-25 08:01:43'),
(70,15,12,12,NULL,'“Tilla tanlashda ayollar orasida yuradigan noto‘g‘ri fikrlar”','Reels','2025-12-16','Malumot beruvchi','2025-11-25 06:53:41','2025-11-25 08:01:55'),
(71,15,12,12,NULL,'“Ayollar vs Erkaklar tilla tanlashda qanday farq qiladi?” ','Reels','2025-12-19','Yumoristik video','2025-11-25 06:55:00','2025-11-25 08:02:10'),
(72,15,12,12,NULL,'“Valyuta o‘zgarishi tilla narxiga qanday ta’sir qiladi?”','Reels','2025-12-22','Informatsion','2025-11-25 06:56:37','2025-11-25 08:02:24'),
(73,15,12,12,NULL,'“Bir qarashda tilla, aslida esa…”','Reels','2025-12-25','Shok kontent','2025-11-25 06:57:59','2025-11-25 08:02:36'),
(74,15,12,12,NULL,'“585 yoki 750?”farqi nimada?','Reels','2025-12-28','Ikkala model farqi haqida','2025-11-25 06:59:53','2025-11-25 08:03:06'),
(75,16,12,12,NULL,'Qishda uchraydigan 5 kasallik va qaysi holatda shifokorga murojaat qilish kerak?','Reels','2025-12-01','Foydali ma`lumotlar mavsumiy kasalliklar ','2025-11-27 07:06:45','2025-12-10 05:51:50'),
(76,16,12,12,NULL,'robot jarrohligi — kesik kichik, aniqlik esa 10 baravar yuqori.','Reels','2025-12-04','Robot Jarrohlik','2025-11-27 07:08:19','2025-11-27 07:43:49'),
(77,16,12,12,NULL,'Shamollashda antibiotik ichish shart degan afsona — real faktlar bilan','Reels','2025-12-07','Afsona va haqiqat','2025-11-27 07:09:56','2025-11-27 07:45:28'),
(78,16,12,12,NULL,'Yurak og‘riyapti — Valeriana ichaman; xato va uning xavfli oqibatlari.','Reels','2025-02-10','foydali ','2025-11-27 07:12:17','2025-11-27 07:46:53'),
(79,16,12,12,NULL,'Murakkab operatsiyalarni robot yordamida o‘tkazish — jarayon qanday olib borilishi haqida','Reels','2025-01-13','operatsiya','2025-11-27 07:14:06','2025-11-27 07:47:46'),
(80,16,12,12,NULL,'Bolaga dori  ko‘p berish mumkin” degan noto‘g‘ri qarash','Reels','2025-01-16','foydali ','2025-11-27 07:16:04','2025-11-27 07:49:40'),
(81,16,12,12,NULL,'Qorin bo‘shlig‘i yoki urologik jarrohlik — robot bilan qisqa demo-kadrlar','Reels','2025-12-19','operatsiya','2025-11-27 07:17:37','2025-11-27 07:51:22'),
(82,16,12,12,NULL,'Shamol tegib buyrak shamollaydi” — real tibbiy sabab nima?','Reels','2025-12-22','afsona va haqiqat','2025-11-27 07:19:46','2025-11-27 07:52:50'),
(83,16,12,12,NULL,'Grippda uy sharoitidagi noto‘g‘ri usullar: nimani qilish mumkin, nimani emas?','Reels','2025-12-25','Foydali / To‘g‘ri-Noto‘g‘ri','2025-11-27 07:22:54','2025-11-27 08:31:20'),
(84,16,12,12,NULL,'Nega robotik jarrohlik tez tiklanish va kam og‘riq beradi? 5 sababi.','Reels','2025-12-28','Robotik / Sabablar','2025-11-27 07:24:16','2025-11-27 08:32:16'),
(85,16,12,12,NULL,'Vitamin C shamollashni davolamaydi — bu faqat profilaktika vositasi.','Reels','2025-12-30','afsona va fakt','2025-11-27 07:25:13','2025-11-27 08:33:44'),
(86,16,12,12,NULL,'Trend vide ','Reels','2025-01-01','trenddagi video meditsinaga adaptatsiya qilish','2025-11-27 07:26:44','2025-11-27 08:34:24'),
(87,18,5,NULL,NULL,'Bismillah ','Post','2025-11-28','Kiruvchi post','2025-11-27 11:49:27',NULL),
(88,18,5,NULL,NULL,'Biznes Kapsula Klubi o\'zi nima?','Post','2025-11-29','Klub haqida ma\'lumot','2025-11-27 11:50:04',NULL),
(89,18,5,NULL,NULL,'Biznesingizni rivojlantirish uchun networking imkoniyati!','Reels','2025-11-30','Akmal aka bilan video: klub beradigan imkoniyat haqida','2025-11-27 14:00:26',NULL),
(90,18,5,NULL,NULL,'Biznes tashrif','Post','2025-12-01','Biznes klub konsepsiyasidagi tashrif haqida ma\'lumot','2025-11-27 14:05:42',NULL),
(91,18,5,NULL,NULL,'Klub sizga nima beradi?','Carousel','2025-12-02','Klub beradigan 6 qiymat ','2025-11-27 14:07:40',NULL),
(92,18,5,NULL,NULL,'Siz klubda qatnasha olmaysiz! Agar...','Reels','2025-12-03','Biznes Kapsula kursida o\'qimagan tadbirkorlar klubda qatnasha olmaydi','2025-11-27 14:12:38',NULL),
(93,18,5,NULL,NULL,'Klubdan tushgan pullar qayerga ketadi?','Reels','2025-12-04','Nega bizda qatnashishingiz kerak degan savolga yana bir javob, bu bizdagi hayriya va ehson ishlariga sherik bo\'lish.','2025-11-27 14:14:42',NULL),
(94,18,5,NULL,NULL,'Rivojlanish uchun aniq maskan! ','Reels','2025-12-05','Klubga chaqiruv ','2025-11-27 14:15:45',NULL),
(95,18,5,5,NULL,'Klub taqdimoti kunidan lavhalar!','Reels','2025-12-06','Klub taqdimot kunidan video lavhalar','2025-11-27 14:17:11','2025-11-27 14:17:23'),
(96,18,5,NULL,NULL,'Klub a\'zolari uchun - Biznes sayohat!','Reels','2025-12-10','Har 3 oyda mahalliy yoki xorijiy joylarga ziyorat, sayohat haqida','2025-11-27 14:19:26',NULL),
(97,19,12,NULL,NULL,'rak boal va motivatsiya','Reels','2025-11-30','motevatsion vide','2025-11-28 07:22:16',NULL),
(98,19,12,12,NULL,'Ifloslangan xavo- Odam salomatligiga qanday ta`sir qiladi?','Reels','2025-12-02','Ifloslangan havo odamaga beradigan zarari va profilaktika usullari haqida ma`lumot beriladi','2025-11-28 07:25:17','2025-11-29 07:18:44'),
(99,19,12,NULL,NULL,'Ko‘krakda tugun sezildi — bu har doim rakmi?','Reels','2025-01-05','Ko‘krakdagi tugunlarning 80% benign ekanini, lekin baribir tekshiruv zarurligini aytasiz','2025-11-28 07:26:48',NULL),
(100,19,12,12,NULL,'Tana nega saratonga ruxsat beradi?','Reels','2025-12-07','Immunitetingiz aslida saratonni yo‘q qilishi kerak edi. Nega uni ko‘rmadi','2025-11-28 07:28:10','2025-11-28 11:48:52'),
(101,19,12,NULL,NULL,'Ayollar orasida eng ko‘p uchraydigan 5 qo‘rquv','Reels','2025-12-10','Haqqiqatda qaysi biri xavfli?','2025-11-28 07:29:04',NULL),
(102,19,12,NULL,NULL,'Erkaklarda prostata bo‘yicha 3 noto‘g‘ri tushuncha','Reels','2025-12-13','Yosh bilan bog‘liq o‘zgarishlar, rak emasligi, tekshiruvning ahamiyati.','2025-11-28 07:30:02',NULL),
(103,19,12,12,NULL,'Odamdan odamga “ko‘chib” o`tgan saraton — dunyodagi eng g‘alati holatlar','Reels','2025-12-13','organ transplantatsiyasi\n\nona–bola yo‘li\n\nimmun tizimi past bo‘lgan shaxslar\norqali saraton hujayrasi boshqa odamga o‘tgan holatlar qayd etilgan.','2025-11-28 07:32:13','2025-11-29 08:33:47'),
(104,19,12,NULL,NULL,'MRT rakni 100% aniqlaydimi?','Reels','2025-12-16','Qachon kerak, qachon foydasiz, boshqa testlar bilan birga.','2025-11-28 07:33:32',NULL),
(105,19,12,12,NULL,'Uy tozalashda ishlatayotgan bu suyuqliklar — siz o‘ylagandan ko‘ra xavfliroq','Reels','2025-12-19','Domestos, Mister Proper, shu kabi kimyoviy vositalarning zarari va profilaktikasiga oid ma`lumot beruvchi reels','2025-11-28 07:34:51','2025-11-29 07:26:44'),
(106,19,12,12,NULL,'Qon bosimi birdan ko‘tarilganda uyda nima qilish kerak?','Reels','2025-12-22','uy sharoitida nmalar qilish to`g`ri nma noto`g`rlihi haqida maslahat','2025-11-28 07:35:47','2025-11-29 07:33:56'),
(107,19,12,12,NULL,'Dunyo tarixidagi eng g‘alati o‘simta','Reels','2025-12-25','tish, soch va suyakdan iborat teratoma','2025-11-28 07:37:05','2025-11-28 12:05:10'),
(108,19,12,12,NULL,'Abadiy yashaydigan yagona inson hujayrasi — saraton hujayrasi','Reels','2025-12-28','rak hujayrasi haqida ma`lumot','2025-11-28 07:39:46','2025-11-28 11:59:29'),
(109,19,12,12,NULL,'Ertalab nonushta qilmaslikning yashirin zararlari','Reels','2025-12-31','ertalabki nonushtaning ahamiyati va foda va zaralari haqida malumot beriladigan post','2025-11-28 07:41:10','2025-11-29 07:58:32'),
(110,19,12,NULL,NULL,'Chekmaydigan odamda ham o‘pka saratoni bo‘lishi mumkin!','Reels','0026-12-30','Ikkinchi darajali tutun, ekologiya, kasbiy ta’sir','2025-11-28 07:46:12',NULL),
(111,19,12,12,NULL,'Nega ob-havo o‘zgarganda og‘riq kuchayadi','Reels','2026-01-06','Ob-havo va bosim o`zgarishi nervlarga tasiri va og`riq keltiruvchi faktorlar haqida ma`lumot beriladi','2025-11-28 07:49:46','2025-11-29 07:40:59'),
(112,20,7,NULL,NULL,'6 oy ichida koreys tilini o\'rganib ko\'ryening nufuzli oli gohlarida o\'qish!','Reels','2025-11-30','Target va reels video ','2025-12-02 10:50:01',NULL),
(113,20,7,7,NULL,'Koreyaga ilk qadamni Mega Foundation bilan qo\'ying','Reels','2025-12-02','Target uchun ham reels uchun ham kontent','2025-12-02 12:07:57','2025-12-29 07:14:43'),
(114,20,7,7,NULL,'Agar sizda topik bo\'lsa!','Reels','2025-12-05','Kam harajat bilan tez ketish usuli!','2025-12-02 12:08:43','2025-12-29 07:15:07'),
(115,20,7,7,NULL,'Koreyaga ketish oson!','Reels','2025-12-08','Taklif post','2025-12-02 12:11:14','2025-12-29 07:15:29'),
(116,20,7,NULL,NULL,'Biz sizga hech kim bermagan taklifni beramiz!','Reels','2025-12-11','Barcha sharoitlar va grant to\'g\'risida','2025-12-02 12:12:41',NULL),
(118,20,7,NULL,NULL,'Koreyaning TOP universitetlari!','Carousel','2025-12-17','Eng kuchli oliygohlar ro‘yxati','2025-12-02 12:18:15',NULL),
(119,20,7,NULL,NULL,'Bank hisobi va ortiqcha xarajatlarsiz — Koreyada talaba bo‘ling!','Reels','2025-12-20','Taklif va target uchun moslangan reels','2025-12-02 12:21:13',NULL),
(120,20,7,NULL,NULL,'iPhone 17 puliga, Koreyada ta’lim oling!','Carousel','2025-12-23','Aktivlikni va talabni oshiruvchi post','2025-12-02 12:36:45',NULL),
(121,20,7,NULL,NULL,'Mega Foundation sizga qanday imkoniyatlar taqdim etadi?','Carousel','2025-12-26','Talabalarga takliflar va qulayliklar to\'g\'risida!','2025-12-02 12:45:39',NULL),
(122,20,7,NULL,NULL,'Chet elda o‘qishni orzu qilasizmi?','Reels','2025-12-28','Talabalar uchun haqiqiy che elda talim va viza masalasini yoritib beruvchi kontent!','2025-12-02 12:49:15',NULL),
(123,20,7,NULL,NULL,'O‘qish jarayonidan eksklyuziv videolavhalar!','Reels','2025-12-30','O\'qishdan jonli video lavhalar!','2025-12-02 12:52:02',NULL),
(124,20,7,NULL,NULL,'Talabalardan jonli izohlar!','Reels','2025-12-31','Ishonch va talabni oshiruvchi video reels','2025-12-02 12:53:10',NULL),
(126,22,7,NULL,NULL,'Nevropatolog o\'zi nima uchun kerak?','Reels','2025-11-27','Podkast video','2025-12-03 08:59:16',NULL),
(127,22,7,NULL,NULL,'Qon bosimingizni tabiiy usullar bilan nazorat qiling!','Carousel','2025-11-30','Foydali post','2025-12-03 09:00:06',NULL),
(128,22,7,NULL,NULL,'Stresli holatlarning asosiy sabablari!','Reels','2025-12-02','Podkast videodan qisqacha','2025-12-03 09:01:07',NULL),
(129,22,7,NULL,NULL,'TUXUMDON KISTASI vaqtida davolanmasa...','Carousel','2025-12-06','Kasalikni oldini olish haqida post','2025-12-03 09:15:20',NULL),
(130,22,7,7,NULL,'Shifokor hayotidan bir kun!','Reels','2025-12-09','Auditoriya uchun qiziq kontent','2025-12-03 09:16:31','2025-12-03 09:26:45'),
(131,22,7,NULL,NULL,'EEG qanday ishlaydi?','Reels','2025-12-12','Ozvuchkali video','2025-12-03 09:18:43',NULL),
(132,22,7,NULL,NULL,'Burun yoki tomoqda noqulaylik sezayapsizmi?','Reels','2025-12-15','Ish jarayonidan qaynoq lavhalar','2025-12-03 09:20:40',NULL),
(133,22,7,NULL,NULL,'Bemorimizdan izoh!','Reels','2025-12-18','Bemordan holisona izoh va jarayon','2025-12-03 09:21:33',NULL),
(134,22,7,NULL,NULL,'Radiologiya bo\'limi!','Reels','2025-12-21','Radiologiya bo\'limidan video lavhalar!','2025-12-03 09:23:39',NULL),
(135,22,7,NULL,NULL,'Ginekologik muammolarga birgalikda chek qo‘yamiz!','Reels','2025-12-24','Ish jarayonidan video lavhalar','2025-12-03 09:25:04',NULL),
(136,22,7,NULL,NULL,'Bayram chgirmasi!','Reels','2025-12-27','Bayram chegirmasi aksiya','2025-12-03 09:27:24',NULL),
(137,22,7,NULL,NULL,'Salomatlik uchun UTT tekshiruvini muntazam o‘tkazib turing!','Reels','2025-12-30','Ish jarayonidan lavhalar','2025-12-03 09:29:38',NULL),
(140,13,7,NULL,NULL,'Eng ko\'p beriladigan savollarga javob beramiz!','Reels','2025-11-22','Mijozlardan kelgan savollarga javob javoblar','2025-12-03 10:16:58',NULL),
(141,13,7,7,NULL,'Kim harakat qilsa g\'alabag erishadi!','Reels','2025-11-29','Qiziqarli video taklif futbol maydonida!','2025-12-03 10:18:17','2025-12-03 10:19:14'),
(142,13,7,NULL,NULL,'Bugun yana qurilishdan gapiramiz!','Reels','2025-12-02','Qurilishdan videolavhalar!','2025-12-03 10:19:07',NULL),
(143,13,7,7,NULL,'Damaslarimiz soni kam qoldi!','Reels','2025-12-05','Damas avtomobillarini kam qolgani haqida va mijozlarni shoshilishga chaqirish uchun taklif video','2025-12-03 12:25:05','2025-12-17 08:42:30'),
(144,23,5,NULL,NULL,'Tozalik siz uchun ','Reels','2025-12-05','DHshihljkhd','2025-12-03 12:27:25',NULL),
(145,23,5,NULL,NULL,'sdadasd','Post','2025-12-12','adsasdasda','2025-12-03 12:27:35',NULL),
(146,13,7,NULL,NULL,'Xonadon olish uchun boshlang\'ich to\'lovga pul yig\'iyabsizmi?','Reels','2025-12-08','Mijozlarga kam to\'lov orqali xonadon xarid qilish taklifi','2025-12-03 12:28:55',NULL),
(147,13,7,NULL,NULL,'Mijozlarimizning samimiy fikrlari!','Reels','2025-12-11','Samimiy izohlar mijozlardan yoki mijozdan!','2025-12-03 12:37:58',NULL),
(148,13,7,7,NULL,'Xonadon olaman deb yig\'ayotgan pulingiz boshqa ehtiyojlaringizga ketiyabdimi?','Reels','2025-12-14','Boshlang\'ich to\'lovsiz ham xonadon xarid qilsa bo\'ladi dedgan video','2025-12-03 12:47:31','2025-12-17 08:35:07'),
(149,13,7,7,NULL,'Xonadon qidiriyabsizmi?','Carousel','2005-12-17','Mijozlarni va qulayliklarni ko\'rsatib beradiagn Karusel','2025-12-03 12:50:30','2025-12-17 08:37:33'),
(150,13,7,7,NULL,'Bunday imkoniyatni sizga biz beramiz!','Reels','2025-12-20','Kvartira + Damas taklifi','2025-12-03 13:04:50','2025-12-17 08:39:12'),
(151,13,7,7,NULL,'Loyhamizning qishki ko\'rinishi!','Reels','2025-12-23','AI orqali video rolik','2025-12-03 13:07:08','2025-12-17 08:40:29'),
(152,24,7,NULL,NULL,'Xizmat ko\'rsatishni qanday baholaysiz?','Reels','2025-11-09','Xizmat sifatini ko\'rsatib beradigan video reels','2025-12-03 13:27:54',NULL),
(153,24,7,NULL,NULL,'Osh tarixi','Reels','2025-11-12','Ozvuchkali osh tarixi haqida qiziqarli video','2025-12-03 13:28:36',NULL),
(154,24,7,NULL,NULL,'Eng mazali osh faqat bizda!','Reels','2025-11-15','Suni intelekt orqali tayyorlangan video','2025-12-03 13:29:33',NULL),
(155,24,7,NULL,NULL,'Eng yaqin do\'stingizga yuborib qo\'ying!','Reels','2025-11-18','Do\'stlarga jo\'natiladigan reels','2025-12-03 13:30:27',NULL),
(156,24,7,NULL,NULL,'Oshqand lazzatning yangi ko\'rinishi!','Reels','2025-11-21','VFX orqali tayyorlangan video','2025-12-03 13:31:30',NULL),
(157,24,7,NULL,NULL,'Siz kutgan yangilik!','Reels','2025-11-25','Zaytun yog\'ida tayyorlangan osh!','2025-12-03 13:32:18',NULL),
(158,24,7,NULL,NULL,'Bir joyda uch hil lazzat!','Animation','2025-11-30','Uch hil oshni ko\'rinishi va taklif uchun animatsiya','2025-12-03 13:33:16',NULL),
(159,24,7,NULL,NULL,'Oshqand TXTY 2025 ko\'rgazmasida!','Reels','2025-12-02','Ko\'rgazmasan video lavhalar','2025-12-03 13:33:59',NULL),
(160,24,7,NULL,NULL,'Haqiqiy oshning siri — haqiqiy mehnat!','Reels','2025-12-05','Osh pazlar bilan oshning tayyorlanish jarayoni!','2025-12-03 13:35:52',NULL),
(161,24,7,NULL,NULL,'Bizning osh — mijozlar qanday baholashdi?','Reels','2025-12-08','Rubrika mijozlardan osh haqida so\'raladigan video reels','2025-12-03 13:38:04',NULL),
(162,25,7,NULL,NULL,'Keramogranit','Post','2025-12-01','Kafel rasmini post','2025-12-04 13:23:40',NULL),
(163,26,5,NULL,NULL,'Zamonaviy hayot uchun yaratilgan uylar! ','Post','2025-12-08','Loyiha tanishtiruvi','2025-12-08 07:18:15',NULL),
(164,26,5,NULL,NULL,' Shahar markazida qulay joylashuv! ','Post','2025-12-10','Joylashuv afzalligi','2025-12-08 07:19:05',NULL),
(165,26,5,5,NULL,'Siz uchun qulay to‘lov shartlari! ','Post','2025-12-12','To‘lov shartlari','2025-12-08 07:20:18','2025-12-08 07:20:28'),
(166,26,5,NULL,NULL,' IIshonch bilan xarid qiling! ','Post','2025-12-14','Kompaniya ishonchliligi','2025-12-08 07:21:39',NULL),
(167,26,5,NULL,NULL,'Farzandingiz kelajagi uchun!','Post','2025-12-16','Oila uchun qulaylik','2025-12-08 07:22:45',NULL),
(168,26,5,NULL,NULL,'1 xonali uy — kimlar uchun qulay?','Post','2025-12-18','Xonadon afzalliklari','2025-12-08 07:28:08',NULL),
(169,27,11,NULL,NULL,'ozish uchun ham suv ichish kerak','Reels','2025-11-18','suvning foydasi haqida video reels','2025-12-09 06:43:53',NULL),
(170,27,11,NULL,NULL,'miyamizning 73% qismi suvdan iborat','Carousel','2025-11-21','suv bilan bog\'liq faktlar','2025-12-09 06:46:23',NULL),
(171,27,11,NULL,NULL,'istalgan ob havoda suvlarni yetkazamiz','Animation','2025-11-24','animation','2025-12-09 06:47:19',NULL),
(172,27,11,NULL,NULL,'hamd suvlari salomatligingiz homiysi','Post','2025-11-27','hamd suvlarining minerallarga boyligini ko\'rsatish','2025-12-09 06:49:19',NULL),
(173,27,11,NULL,NULL,'ofis va korxonalar uchun bepul kuller','Post','2025-11-30','target post','2025-12-09 06:50:07',NULL),
(174,27,11,NULL,NULL,'qishda ham suv iching ','Reels','2025-12-03','qishda suv ichish kasalliklar oldini olishi haqida reel','2025-12-09 06:51:17',NULL),
(175,27,11,NULL,NULL,'hamd — oilangiz salomatligi uchun','Post','2025-12-06','oila salomatligi uchun ham suv ichish haqida post','2025-12-09 06:52:17',NULL),
(176,27,11,NULL,NULL,'nega qishda odamlar ko\'p kasal bo\'ladi?','Reels','2025-12-09','qishda ko\'p kasal bo\'lish sababi suv kam ichishligini ko\'rsatish','2025-12-09 06:54:16',NULL),
(177,27,11,NULL,NULL,'ramazon oyi yaqin','Reels','2025-12-12','target reel','2025-12-09 07:02:23',NULL),
(178,25,7,NULL,NULL,'Tashqari qisimlar uchun kafellar!','Carousel','2025-12-09','Karusel post pol uchun kafellar','2025-12-09 07:11:53',NULL),
(179,25,7,NULL,NULL,'Keramogranit ','Post','2025-12-07','post kafel rasmi','2025-12-09 07:12:28',NULL),
(180,25,7,NULL,NULL,'Nafis va zamonaviy panogullar!','Carousel','2025-12-08','Kafellar rasmi bilan karusel post','2025-12-09 07:13:23',NULL),
(181,25,7,NULL,NULL,'Keramogranit','Post','2025-12-10','post kafel rasmi','2025-12-09 07:13:46',NULL),
(182,25,7,NULL,NULL,'Hozirgi vaqtda trenddagi travertin toshlari!','Reels','2025-12-12','Travertin toshlari ulgurchi narxlarda','2025-12-09 07:14:36',NULL),
(183,25,7,NULL,NULL,'Keramogranit ','Post','2025-12-14','kafel rasmi ','2025-12-09 07:14:56',NULL),
(184,25,7,NULL,NULL,'Ustalar va quruvchilar uchun maxsus taklif!','Reels','2025-12-16','Kafel va granitni kesib berish va hohlagan usulda tayyorlab berish haqida','2025-12-09 07:15:48',NULL),
(185,25,7,NULL,NULL,'Keramogranit','Post','2025-12-18','Kafel rasmi','2025-12-09 07:16:14',NULL),
(186,27,11,NULL,NULL,'barcha distribyuterlar uchun ajoyib taklif ','Reels','2025-12-15','target video','2025-12-09 07:16:28',NULL),
(187,25,7,NULL,NULL,'Bu haqiqiy tabiat yaratgan mo‘jiza!','Reels','2025-12-20','Sifatli granitlar va ularning ulgurchi narxi haqida','2025-12-09 07:17:00',NULL),
(188,25,7,NULL,NULL,'Keramogranit','Post','2025-12-22','Kafell rasmi','2025-12-09 07:17:31',NULL),
(189,25,7,NULL,NULL,'Minimalizm va estetika ixlosmandlari uchun — siz izlayotgan eng zo‘r kafel aynan bizda!','Reels','2025-12-23','Estetik kafellar video obzori','2025-12-09 07:18:26',NULL),
(190,25,7,NULL,NULL,'Keramogranit','Post','2025-12-25','Kafell rasmi','2025-12-09 07:18:52',NULL),
(191,28,11,NULL,NULL,'siz istagan turdagi korporativ setlar','Post','2025-11-03','korporativ premium setni ko\'rsatish','2025-12-09 07:19:46',NULL),
(192,25,7,NULL,NULL,'Bayram O‘shomi — Energiya To‘la Kecha!','Reels','2025-12-27','Bayram ohshomidan videorolik','2025-12-09 07:20:14',NULL),
(193,28,11,NULL,NULL,'bizning showroom','Reels','2025-11-06','showrom obzori','2025-12-09 07:20:26',NULL),
(194,25,7,NULL,NULL,'Keramogranit','Post','2025-12-29','\nKafel rasmi','2025-12-09 07:20:44',NULL),
(195,28,11,NULL,NULL,'100lab brendlar ishonchini oqlagan brend','Reels','2025-11-12','promo lux bn tanishuv','2025-12-09 07:21:17',NULL),
(196,28,11,NULL,NULL,'brend bu kuch','Reels','2025-11-15','logo tushurilgan sovg\'alar nimaga kerak degan savolga javob berish','2025-12-09 07:24:44',NULL),
(197,29,7,NULL,NULL,'Energiyaning tabiiy manbai!','Animation','2025-12-01','AI video','2025-12-09 07:25:17',NULL),
(198,29,7,NULL,NULL,'Har kechani bayramga aylantiring!','Post','2025-12-04','Kreativ va minimalizim stilida post','2025-12-09 07:26:09',NULL),
(199,28,11,NULL,NULL,'sovg\'a izlab ovora bo\'lmang','Reels','2025-11-18','target','2025-12-09 07:26:50',NULL),
(200,29,7,NULL,NULL,'Har tomchida yengillik va nafis ta\'m!','Carousel','2025-12-07','Mahsulotning har hil lokatsiyada ko\'rinishi','2025-12-09 07:27:10',NULL),
(201,28,11,NULL,NULL,'korporativ bayram setlar','Reels','2025-11-21','target','2025-12-09 07:28:00',NULL),
(202,29,7,NULL,NULL,'Kundalik energiya tabiiy suvdan boshlanadi!','Reels','2025-12-09','Tongni suvdan boshlang','2025-12-09 07:28:12',NULL),
(203,28,11,NULL,NULL,'siz izlagan biznes sovg\'alar ','Reels','2025-11-24','showrom abzor','2025-12-09 07:28:53',NULL),
(204,29,7,NULL,NULL,'Tabiatning sof lazzati!','Animation','2025-12-12','AI stilida video','2025-12-09 07:29:25',NULL),
(205,28,11,NULL,NULL,'samarqandliklar uchun ajoyib taklif','Reels','2025-11-27','target ','2025-12-09 07:29:44',NULL),
(206,28,11,NULL,NULL,'barcha korxonalar uchun taklif','Reels','2025-11-30','target','2025-12-09 07:30:23',NULL),
(207,28,11,NULL,NULL,'axclusive to\'plam','Reels','2025-12-03','to\'plam abzor','2025-12-09 07:35:06',NULL),
(208,28,11,NULL,NULL,'brendingiz tushurilgan termoslar','Reels','2025-12-06','termoslar ko\'rsatilgan trend video','2025-12-09 07:35:48',NULL),
(209,28,11,NULL,NULL,'premium to\'plam','Reels','2025-12-09','to\'plam abzor','2025-12-09 07:36:39',NULL),
(222,31,11,NULL,NULL,'FBS blok qaysi poydevorlar uchun to‘g‘ri keladi?','Reels','2025-12-12','Qanday bino turi uchun FBS ishlatilishi mumkin va qachon mumkin emasligi tushuntiriladi','2025-12-09 11:15:28',NULL),
(223,31,11,NULL,NULL,'Beton plita nega cho\'kib qolmaydi?','Reels','2025-12-15','Tajriba ko‘rinishidagi rolik: plita ustiga og‘ir texnika chiqib yoki yuk terib mustahkamlik isbotlanadi','2025-12-09 11:17:00',NULL),
(224,31,11,NULL,NULL,'Beton tayyorlash sahna ortida','Reels','2025-12-18','Zavod ichidagi beton aralashtirish, sifat nazorati jarayoni cinematografik uslubda','2025-12-09 11:21:06',NULL),
(225,31,11,NULL,NULL,'Noto‘g‘ri beton — yaroqsiz bino','Reels','2025-12-21','Past sifat beton oqibatlari, Zarafshon Group standarti bilan taqqoslanadi','2025-12-09 11:22:20',NULL),
(226,31,11,NULL,NULL,'FBS blok qanday yerlarga to\'g\'ri kelmaydi?','Carousel','2025-12-17','Geologik sharoit va xatolar infographic','2025-12-09 11:26:05',NULL),
(227,31,11,NULL,NULL,'Beton plita necha yil xizmat qiladi?','Carousel','2025-12-18','sifatli betonning umr ko‘rish muddati grafik bilan','2025-12-09 11:27:10',NULL),
(228,31,11,NULL,NULL,'M200, M300, M400 beton qayerda ishlatiladi?','Carousel','2025-12-20','Har markaning funksional xaritasi ko\'rsatiladi','2025-12-09 11:28:18',NULL),
(229,31,11,NULL,NULL,'Zarafshon Group nega boshqalardan farq qiladi?','Post','2025-12-22','Ustunliklar: sifat, logistika, texnika, tajriba','2025-12-09 11:39:14',NULL),
(230,31,11,NULL,NULL,'Quruvchilar uchun lifehack','Carousel','2025-12-25','Beton quyishda qilinadigan xatolar va yechimlar','2025-12-09 11:40:03',NULL),
(231,31,11,NULL,NULL,'Real obyekt — real natija','Carousel','2025-12-25','Qurilish jarayoni','2025-12-09 11:51:57',NULL),
(232,29,7,NULL,NULL,'Minerallarga boy, sof va toza suv!','Post','2025-12-15','Minimalizm stilda post','2025-12-09 13:04:34',NULL),
(233,29,7,NULL,NULL,'Sifatli suv — kuchli jamoa va samarali ishning garovi!','Reels','2025-12-18','Kapsula suvini tadbirkorlarga taklif qilish!','2025-12-09 13:06:48',NULL),
(234,29,7,NULL,NULL,'Bayram kayfiyatini ikki baravar oshir!','Animation','2025-12-21','AI video','2025-12-09 13:08:06',NULL),
(235,29,7,NULL,NULL,'Tabiatdan olingan sof energiya — har kuni siz bilan!','Reels','2025-12-24','Kapsula suvlari bilan video','2025-12-09 13:09:39',NULL),
(236,29,7,NULL,NULL,'Tabiat hadyasi — haqiqiy Samarqand suvi!','Post','2025-12-27','Registon maydonida suv bilan ai generatsiya','2025-12-09 13:11:56',NULL),
(237,29,7,NULL,NULL,'Kun davomida tetik bo‘lishning eng tabiiy yo‘li!','Reels','2025-12-29','Suv idishi bilan trend video ','2025-12-09 13:14:19',NULL),
(238,29,7,NULL,NULL,'Butun oila uchun tabiat musaffoligi!','Animation','2025-12-30','Animatsiya shaklida video ','2025-12-09 13:20:52',NULL),
(239,32,7,7,NULL,'Ishdan keyingi eng mazali yo‘l — “Osh Qand”!','Reels','2025-12-10','Ikki do\'st bilan olinadigan video','2025-12-09 13:46:23','2025-12-17 07:57:51'),
(240,32,7,NULL,NULL,'Qozondan to‘g‘ridan-to‘g‘ri stolingizga!','Reels','2025-12-12','Juma nomozdan keyin navbatlarsiz osh ','2025-12-09 13:49:21',NULL),
(241,32,7,NULL,NULL,'Samarqandga keldingizmi?','Reels','2025-12-15','Brand face yoki bloger bilan video olinadi!','2025-12-09 13:51:36',NULL),
(242,32,7,NULL,NULL,'Sovuq kunda — issiq malinali choy!','Reels','2025-12-18','Osh bilan malinali choy tortiq qilinadi','2025-12-09 13:53:08',NULL),
(248,33,8,8,NULL,'Gamburger apparati uchun ten','Post','2026-01-07','Ten va apparatni fonda ko\'rsatish','2025-12-10 12:14:27','2026-01-06 08:58:46'),
(254,33,8,NULL,NULL,'Avtoklav apparati animatsiyasi','Animation','2026-01-09','Ishlatib animatsiya qilinadi','2025-12-10 12:26:15',NULL),
(256,32,7,NULL,NULL,'Uyda osh tayyorlashni o\'rganamiz!','Reels','2025-12-20','Oshpaz bilan birorta yaxshi lokatsiyada osh tayyorlaymiz!','2025-12-10 13:36:47',NULL),
(257,32,7,NULL,NULL,'Darsdan keyin qayerga boramiz?','Reels','2025-12-23','Talabalar bilan tushlik darsdan keyin!','2025-12-10 14:20:26',NULL),
(258,34,7,NULL,NULL,'Shoxona bilan shoxona kayfiat!','Post','2025-12-11','Grafik post mahsulotni yorqin rangda ko\'rsatish','2025-12-11 13:09:53',NULL),
(259,34,7,NULL,NULL,'Do\'stlar bilan yanada mazzaliroq','Reels','2025-12-14','Maktab bollari tushlik uchun sendvich tanavul qilishmoqda','2025-12-11 13:11:19',NULL),
(260,34,7,NULL,NULL,'Sizchi tatib ko\'rdingizmi?','Reels','2025-12-17','Ko\'chadi insonlarga sendvichlar ulashamiz va ta\'mi, mazasi haqida so\'raymiz','2025-12-11 13:12:48',NULL),
(261,34,7,NULL,NULL,'Siz qaysi birini tanlaysiz?','Animation','2025-12-20','Sendvichlarni asortementini ko\'rsatish moushn post qilib!','2025-12-11 13:34:56',NULL),
(262,34,7,NULL,NULL,'Hamkorlikni taklif qilamiz!','Reels','2025-12-23','Do\'kon egalari va marketlar uchun hamkorlik taklifi!','2025-12-11 13:40:34',NULL),
(263,34,7,NULL,NULL,'Shoxona ta’m, shoxona tanlov!','Post','2025-12-26','Mahsulotni oila davrasida ko\'rsatib beruvchi post!','2025-12-11 13:45:55',NULL),
(264,34,7,NULL,NULL,'To‘yimli va mazali tovuqli sendvich!','Carousel','2025-12-29','Sendvich haqida ma\'lumot beramiz istemolchilarga','2025-12-11 13:57:08',NULL),
(265,34,7,NULL,NULL,'Shoxona sendvich — vaqtingizni va naqtingizni tejaydi!','Reels','2026-01-01','Ofisda ishlaydigan insonlar uchun juda ham mos tushlik','2025-12-11 14:05:05',NULL),
(266,34,7,NULL,NULL,'Shoxona — ishonchli tanlov!','Reels','2026-01-04','Sendvichni ishlab chiqarilganidan to sotib olinib tanavul qilinganicha video reels','2025-12-11 14:09:34',NULL),
(267,34,7,NULL,NULL,'To‘g‘ri javob — sendvich sizniki!','Reels','2026-01-07','Ko\'chadagi insonlarga savol va to\'g\'ri javob uchun sendvich','2025-12-11 14:12:02',NULL),
(268,34,7,NULL,NULL,'Shoxona — mening tanlovim!','Reels','2026-01-08','Yosh bola yaniy qizaloq bilan sendvic obzorini yoki ishlab chiqarishini ko\'rsatamzi','2025-12-11 14:16:31',NULL),
(269,34,7,NULL,NULL,'O‘yin davomida to‘yimli sendvich — eng zo‘ri tanlov!','Reels','2026-01-11','Game Clubda o\'yin o\'ynab otirganlar uchun zo\'r tanlov bu sendvich shuni ko\'rsatib beramiz','2025-12-11 14:22:41',NULL),
(272,37,13,NULL,NULL,'Barcha turdagi kanselyariya bir joyda!','Reels','2025-12-16','senariy buyicha','2025-12-15 17:38:39',NULL),
(273,37,13,NULL,NULL,'Har til, har did uchun kitoblar','Reels','2025-12-19','senariy buyicha','2025-12-15 17:40:34',NULL),
(274,37,13,13,NULL,'Siz qaysi turdagi kitoblarni ko‘proq o‘qiysiz?','Post','2025-12-22','🔘 Badiiy adabiyot\n🔘 O‘zini rivojlantirish\n🔘 Detektiv / Fantastika\n🔘 Bolalar kitoblari\n🔘 Ilmiy / o‘quv adabiyotlar\n\n👇 Izohlarda yozib qoldiring — sizga mos kitoblarni tavsiya qilamiz!','2025-12-15 17:41:53','2025-12-15 17:42:32'),
(275,37,13,NULL,NULL,'Har qanday narsani sotishni o’rganish uchun top 3 kitob','Reels','2025-12-26','senariy smm chatga berilgan','2025-12-15 17:46:19',NULL),
(276,37,13,NULL,NULL,'Tavsifga qarab kitobni toping','Carousel','2025-12-30','Kichkina qahramon sayyoralar bo‘ylab sayohat qiladi\nva kattalarga muhim saboqlar beradi.\n❓ Tanidingizmi?\n','2025-12-15 17:50:06',NULL),
(277,37,13,NULL,NULL,'Yangi Yil bayram ','Animation','2025-12-31','yangi yil bayram animatsiya','2025-12-15 17:50:36',NULL),
(278,37,13,NULL,NULL,'Kitob o’qish nima uchun foydali?','Reels','2026-01-04','senariy buyicha ','2025-12-15 17:56:08',NULL),
(279,37,13,NULL,NULL,'Bizda bolalaringiz uchun barcha zarur mahsulotlar mavjud','Reels','2026-01-07','bolalar rivoji mahsulotlari haqida ','2025-12-15 18:00:52',NULL),
(280,37,13,NULL,NULL,'Ya’qinlaringizni kitob bilan quvontiring','Carousel','2026-01-10','Har did va har yosh uchun kitoblar','2025-12-15 18:05:59',NULL),
(281,37,13,NULL,NULL,'Kitob beraymi yoki gul mi?','Reels','2026-01-13','https://www.instagram.com/reel/DR9zS6giLsX/?igsh=MTQ5eWlhcmhyOGZieQ==','2025-12-15 18:10:47',NULL),
(282,32,7,7,NULL,'Buyurtma qiling — qozondan to‘g‘ri dasturxoningizga!','Reels','2025-12-26','Yetkazib berish xizmatini ko\'rsatib berish!','2025-12-17 07:56:25','2025-12-17 08:04:43'),
(284,32,7,NULL,NULL,'Oshimizni mijozlar qanday baholashadi!','Reels','2025-12-29','Mijozlardan holisona izohlar','2025-12-17 08:04:35',NULL),
(285,32,7,NULL,NULL,'Haqiqiy sharqona taom!','Reels','2025-12-31','Erta tongdan osh tayyorlanishi va zagatovka jarayoni','2025-12-17 08:11:30',NULL),
(286,32,7,NULL,NULL,'Trend bo‘lsa — trenda!','Reels','2026-01-03','AI orqali trend video tayyorlash','2025-12-17 08:14:34',NULL),
(287,40,7,NULL,NULL,'Orzu qilingan hayot - Kattakurgan City\'dan boshlanadi!','Reels','2025-12-30','Mijozlarni qiynayotgan muamo bilan olingan videoreels','2025-12-17 08:48:38',NULL),
(288,40,7,NULL,NULL,'Mijozlarimiznig samimiy fikirlari!','Reels','2026-01-01','Mijozlardan xonadonlar va ishonch haqida so\'raymiz','2025-12-17 08:49:31',NULL),
(289,40,7,NULL,NULL,'Qurilishdan qaynoq lavalar!','Reels','2026-01-04','Qurilishdan jonli videolavhalar!','2025-12-17 08:50:15',NULL),
(290,40,7,NULL,NULL,'Hovli yoki kvartira?','Reels','2026-01-07','Aholi bilan savol javob','2025-12-17 08:51:12',NULL),
(291,41,12,NULL,NULL,'Bemorlar eng ko‘p kechiktiradigan 3 ta tekshiruv','Reels','2026-01-07','Bu video odamlar qaysi tekshiruvlarni doim ortga surishini tushuntiradi va nega aynan shu tekshiruvlar muhimligini aytadi.\nGormonal va umumiy qon tahlillari\nYurak-qon tomir tekshiruvlariOnkologik skrininglar','2026-01-03 09:58:08',NULL),
(292,41,12,NULL,NULL,'Shifokorga kelishdan oldin qilinadigan xatolar','Reels','2026-01-09','Bu mavzu odamlar shifokorga kelishdan oldin o‘zlariga o‘zlari qanday zarar yetkazishini ochib beradi.\n1️⃣ Google tashxisiga ishonish\n2️⃣ Og‘riq qoldiruvchi ichib kelish\n3️⃣ “O‘zi o‘tib ketadi” deb kutish\n4️⃣ Tahlilsiz va hujjatsiz kelish\n5️⃣ Belgilarni yashirish yoki kamaytirib aytish\n6️⃣ O‘zicha dori ichishni boshlash','2026-01-03 10:03:30',NULL),
(293,41,12,NULL,NULL,'belgi bermaydigan kassaliklar','Reels','2026-01-11','Bu video kasalliklar har doim og‘riq bilan boshlanmasligini, lekin baribir xavfli bo‘lishi mumkinligini tushuntiradi.','2026-01-03 10:07:55',NULL),
(294,41,12,NULL,NULL,'Odamlar sog‘ligini qachon jiddiy qarashadi?','Reels','2026-01-13','Bu mavzu odamlar qaysi pallada sog‘lig‘iga e’tibor bera boshlashini, ko‘pincha nega kech bo‘lishini yoritadi.','2026-01-03 10:14:12',NULL),
(295,41,12,NULL,NULL,'“Ko‘pchilik bilmaydi, lekin…”','Reels','2026-01-15','Bu mavzu odamlar ko‘p bilmaydigan, lekin muhim faktni ochib beradi.','2026-01-03 10:22:28',NULL),
(296,41,12,NULL,NULL,'Qachon darhol shifokorga murojaat qilish kerak?','Reels','2026-01-17','Bu mavzu qaysi holatlarda kutish xavfli ekanini aniq qilib ko‘rsatadi.','2026-01-03 10:24:51',NULL),
(297,41,12,NULL,NULL,'operatsiya','Reels','2026-01-19','operatsiya jarayoni reels uchun','2026-01-03 10:26:17',NULL),
(298,41,12,NULL,NULL,'operatsiya ','Reels','2026-01-21','operatsiya videosi','2026-01-03 10:27:19',NULL),
(299,41,12,NULL,NULL,'operatsiya ','Reels','2026-01-23','operatsiya','2026-01-03 10:28:09',NULL),
(300,41,12,NULL,NULL,'operatsiya','Reels','2026-01-25','operatsiya','2026-01-03 10:28:40',NULL),
(301,41,12,12,NULL,'Kasallik nega boshida bilinmaydi?','Reels','2026-01-27','Bu video kasalliklarning dastlab yashirin kechish sababini tushuntiradi.','2026-01-03 10:29:18','2026-01-03 10:30:23'),
(302,41,12,NULL,NULL,'Ko‘p bemorlar shunday keladi…','Reels','2026-01-29','Bu video shifokorlar ko‘p ko‘radigan umumiy holatlar haqida, odamlar o‘zini tanib oladi.','2026-01-03 10:41:07',NULL),
(303,42,12,NULL,NULL,'Yurakni sog‘lom saqlash uchun kundalik 5 odatlar','Reels','2026-01-06','Yurak va qon-tomir kasalliklari va ularni profilaktika qiluvchi odatlar haqida video rolik','2026-01-03 11:30:18',NULL),
(304,42,12,NULL,NULL,'Uyda davolanishda qilinadigan eng ko‘p qilinadigan xatolar','Reels','2026-12-09','Top 3 xato ','2026-01-03 11:32:36',NULL),
(305,42,12,NULL,NULL,'Bu 5 odat Metabolizmni tezlashtiradi','Reels','2026-01-11','Ortiqcha vazn / metabolik sindrom “Kundalik 5 daqiqa — va sizning metabolizmingiz o‘zgaradi!”','2026-01-03 11:35:13',NULL),
(306,42,12,NULL,NULL,'Bu belgilarni ko‘rib ham e’tibor bermaslik hayotingizga xavf tug‘diradi!”','Reels','2026-01-13','Top 3 xato: Oddiy belgilarni e’tiborsiz qoldirish (onkologiya)','2026-01-03 11:36:30',NULL),
(307,42,12,NULL,NULL,'Bu 5  ta usul aniq stressni kamaytiradi va uyquni yaxshilaydi','Reels','2026-01-15','op 5 maslahat: Stress va uyqusizlikni kamaytirish bo‘yicha amaliy tavsiyalar','2026-01-03 11:41:23',NULL),
(308,42,12,NULL,NULL,'“Bu oddiy ovqat sizning metabolizmingizni shunday o‘zgartiradi!”','Reels','2025-01-17','Qiziqarli fakt: Ovqatlanish va metabolizm o‘rtasidagi hayratlanarli bog‘liqlik','2026-01-03 11:42:22',NULL),
(309,42,12,NULL,NULL,'“Hazmni yaxshilash uchun har kuni shu 5 narsani qiling!”','Reels','2026-01-19','Top 5 maslahat: Oshqozon va ichakni sog‘lom saqlash bo‘yicha amaliy tavsiyalar','2026-01-03 11:47:18',NULL),
(310,42,12,NULL,NULL,'Siz har kecha shunday qilasiz, va  bu sizning uyquingizni buzadi!”','Reels','2026-01-21','op 3 xato: Telefon bilan uxlash kech uxlash suv ichmasdan uxlash','2026-01-03 11:49:58',NULL),
(311,42,12,NULL,NULL,'“Bu oddiy tekshiruv hayotingizni saqlashi mumkin!”','Reels','2026-01-23','Top 5 maslahat: Ayollar va erkaklar uchun onkologiya skrining bo‘yicha amaliy maslahatlar','2026-01-03 11:52:56',NULL),
(312,42,12,NULL,NULL,'“Siz har kuni shunday qilasiz, ammo bu ko‘z va quloqingizga zarar keltiradi!”','Reels','2026-01-25','Top 3 xato: Telefon va kompyuter bilan noto‘g‘ri odatlar (ko‘rish va eshitish)','2026-01-03 11:55:18',NULL),
(313,42,12,NULL,NULL,'Har kuni 5 daqiqa shu mashqni bajaring va bel o`g`riqlaridan xalos bo`ling”','Reels','2025-01-27','Top 5 maslahat: Orqa va bo‘g‘imlarni sog‘lom saqlash bo‘yicha mashqlar','2026-01-03 12:22:30',NULL),
(314,42,12,NULL,NULL,'“Shirinlikni noto‘g‘ri kamaytirish sizga qanday zarar keltirishi mumkin?”','Reels','2026-01-29','Top 3 xato: Shirinliklarni noto‘g‘ri kamaytirish (diabet)\n\nYo‘nalish: Qandli diabet','2026-01-03 12:24:02',NULL),
(315,42,12,NULL,NULL,'Sizni kasalliklardan himoya qiladigan 5 oddiy amaliy maslahat”','Reels','2026-01-30','Top 5 maslahat: Immunitetni tabiiy yo‘l bilan mustahkamlash\n\nYo‘nalish: Allergiya va immunitet muammolari','2026-01-03 12:25:46',NULL),
(316,42,12,NULL,NULL,'“Sizning teringiz va kayfiyatingiz bir-biri bilan shunday bog‘langan!”','Reels','2026-01-31','Qiziqarli fakt: Stress va teri muammolari o‘rtasidagi bog‘liqlik\n\nYo‘nalish: Dermatologik muammolar / ruhiy salomatlik','2026-01-03 12:50:40',NULL),
(317,42,12,NULL,NULL,'ko‘z va quloqingizni himoya qiladigan top 5  ta maslahatlar','Reels','2026-01-31','Top 5 maslahat: Ko‘z va quloq sog‘lomligini saqlash uchun oddiy mashqlar\n\nYo‘nalish: Ko‘rish va eshitish','2026-01-03 12:53:36',NULL),
(318,43,12,NULL,NULL,'Parkovka haqida','Reels','2026-01-08','TJM lardagi parkovka bilan bog`liq muammo va Garden Avenue dan shu kabi muammo yechimi ko`rsatilgan video reels','2026-01-06 07:01:08',NULL),
(319,43,12,NULL,NULL,' rejalashtiryapsizmi?','Reels','2026-01-10','GArden Avenue TJM ning qulay infro tuzulmada joylashganini yorituvchi video reels','2026-01-06 07:03:05',NULL),
(320,43,12,NULL,NULL,'Agar siz qulay va ishonchli xonadon izlayotgan bo‘lsangiz','Reels','2026-01-13',' loihani PIshiq G`ishtdan qurilgan va ishnchli ekanligini yorituvchi video reels','2026-01-06 07:05:12',NULL),
(321,43,12,NULL,NULL,'Uy xaridini ortga surish ko‘pincha foyda emas, aksincha zarar keltiradi.','Reels','2026-01-16','Qurilishning erta bosqichida uy xarid qilishning iqtisodiy taraflam foydali ekanligini yorituvchi video reels','2026-01-06 07:08:36',NULL),
(322,43,12,NULL,NULL,'12-qavatli zamonaviy majmua','Reels','2026-01-19','Loihani ustun jihatlarini yorituvchi reels','2026-01-06 07:36:11',NULL),
(323,43,12,NULL,NULL,'Foydali taklif!','Post','2026-01-22','Xonadonlarni foizsiz muddatli to`lovga xarid qilish imkoni haqida ma`lumot beruvchi post\n','2026-01-06 07:39:42',NULL),
(324,43,12,NULL,NULL,'kelajak uchun ishonchlin sarmoya','Post','2026-01-25','Aynan Qorasuv massividan Garden Avenue dan uy xarid qilishning foidali tomonini yorituvchi post','2026-01-06 07:41:43',NULL),
(326,33,8,NULL,NULL,'Sauna tenlariga suv purkash','Reels','2026-01-15','Sauna tenlarida suv purkashda chelaklab quymaslik haqida.','2026-01-06 09:01:13',NULL),
(327,33,8,NULL,NULL,'Tenlar O\'zbekistonda ishlab chiqariladi','Post','2026-01-18','Target: Fonda eng mashhur apparatlar va ularni tenlari. Hamda bu post butun Uzb bo\'ylab target qilinadi.','2026-01-06 09:03:20',NULL),
(328,30,11,NULL,NULL,'Markazda yashash – faqat boylar uchun emas','Reels','2026-01-10','G‘oya:\nKamera shahar markazi → odamlar shoshmoqda → keskin switch: yangi uylar.\n\nVoice-over:\n\n“Samarqand markazida yashash qimmat deb o‘ylaysizmi?\nMemar Development bu fikrni o‘zgartirdi.”\n\nUrg‘u:\n✔ Markaz\n✔ 60 oy\n✔ Foizsiz','2026-01-06 09:50:40',NULL),
(329,30,11,NULL,NULL,'Tumanlik oila uchun markazdagi imkoniyat','Reels','2026-01-14','G‘oya:\nTumanlik oila ertalab markazga kelmoqda (ish, bozor, maktab).\nOxirida: “Endi qatnab yurmaymiz.”\n\nMessage:\n\n“Biz markazga ko‘chdik.\nNarxi esa tuman narxiday.”\n\nEmotsiya:\nQulaylik + vaqt tejalishi','2026-01-06 09:53:22',NULL),
(330,30,11,NULL,NULL,'60 oy – bu ijaradan qutulish formulasi','Reels','2026-01-16','G‘oya:\nOyma-oy ijara sanalaydi → o‘sha summa uyga ketmoqda.\n\nText on screen:\n“Bir xil to‘lov.\nLekin biri begona uyga, biri o‘zingnikiga.”\n\nFinal:\nKalit topshirish sahnasi','2026-01-06 09:54:15',NULL),
(331,30,11,NULL,NULL,'8000 oila – markazdagi yangi hayot','Reels','2026-01-18','G‘oya:\nKadrlar tez almashadi:\n– Yosh oila\n– Talaba aka-uka\n– Kichkina biznes egasi\n– Bolali ota-ona\n\nVoice-over:\n\n“Bu shunchaki uylar emas.\nBu Samarqand markazidagi yangi shaharcha.”','2026-01-06 09:55:10',NULL),
(332,30,11,NULL,NULL,'Samarqand markazida — qulay shartlarda','Post','2026-01-19','Vizual:\nMarkaz fonida bino\n\nOverlay:\n📍 Shahar markazi\n🕒 60 oy\n🚫 Foizsiz','2026-01-06 10:13:44',NULL),
(333,30,11,NULL,NULL,'Markaz narxi emas, MARKAZ hayoti','Post','2026-01-19','Vizual:\nMinimalistik, premium ko‘rinish\n\nText:\n\n“Narx qulay.\nJoylashuv markaz.”','2026-01-06 10:15:42',NULL),
(334,30,11,NULL,NULL,'Tuman aholisi ham markazda yashashi mumkin','Post','2026-01-20','Vizual:\nOddiy oilaviy obraz (sun’iy luksiz)\n\nMessage:\nBoshlang‘ich to‘lov bilan\nHar kim uchun ochiq imkoniyat','2026-01-06 10:16:31',NULL),
(335,30,11,NULL,NULL,'8000 oilaga mo‘ljallangan rejalashtirilgan shaharcha','Post','2026-01-26','Vizual:\nMaster-plan / umumiy ko‘rinish\n\nText:\nUy + infratuzilma + jamoa\nHammasi markazda','2026-01-06 10:17:17',NULL),
(336,46,7,NULL,NULL,'“Bu talaba Koreyaga ketolmagan. Sababini bilasizmi?”','Reels','2026-01-03',' “Hujjat topshirdi\"\n “TOPIK yo‘q edi ”\n “Pul yetmadi”\n “Mega Foundation bilan qayta urinib ko‘rdi ”\nNatija (TOPIK, viza, Koreya)','2026-01-09 06:38:10',NULL),
(337,46,7,NULL,NULL,'Koreyaga o‘qishga ketish uchun shular SHART EMAS','Reels','2026-01-06','Millionlab pul\nTanish-bilish\nMukammal koreys tili\n 3–4 yil kutish\n\nTo‘g‘ri yo‘l va tayyorgarlik kerak.\nBu yo‘l — Mega Foundation.','2026-01-09 06:47:16',NULL),
(338,46,7,NULL,NULL,'Farzandingiz Koreyada o‘qisa, siz nimani yutasiz?','Carousel','2026-01-09','Xavfsiz kelajak\nDiplom\nMas’uliyatli va o‘z qaroriga ega farzand\nRasmiy ish imkoniyati','2026-01-09 06:56:00',NULL),
(339,46,7,NULL,NULL,'Hamma ham koreaga ketolmaydi. Sababi pul emas.','Reels','2026-01-12','Ba’zilar shoshadi.\nBa’zilar kutib yuradi.\nBa’zilar noto‘g‘ri joyga ishonadi.\nNatija — tayyorgarlik bilan keladi.\ntayyorgarlikni esa mega founadation bilan oling','2026-01-09 07:12:04',NULL),
(340,46,7,NULL,NULL,'Agar siz ota-ona bo‘lsangiz, bu siz uchun.','Reels','2026-01-15','Farzandni uzoqqa yuborish oson emas.\nEng muhimi — rasmiylik, nazorat va aniq yo‘l.\nShu sabab ota-onalar Mega Foundation’ni tanlaydi.','2026-01-09 07:15:19',NULL),
(341,46,7,NULL,NULL,'   Farzandingizni 6 oyda tayyorlab yuborish mumkinmi?','Reels','2026-01-18',' rasmiy yo‘l bo‘lsa\n nazorat bo‘lsa\n aniq reja bo‘lsa','2026-01-09 08:55:37',NULL),
(342,46,7,NULL,NULL,'Nega  6 oy?','Carousel','2026-01-21','Chunki biz:\n— ortiqcha cho‘zmaymiz\n— faqat keraklisini o‘rgatamiz\n— vaqtni yo‘qotmaymiz','2026-01-09 09:00:59',NULL),
(343,46,7,NULL,NULL,'“KOREYAGA KETISH — QADAMLAR”','Carousel','2026-01-24','Qaror\n\nTayyorgarlik\n\nHujjat\n\nNatija','2026-01-09 09:04:28',NULL),
(344,46,7,NULL,NULL,'“KIMLAR KETOLMAYDI?”','Carousel','2026-01-27','Kutib yuradiganlar\n\nRejasizlar\n\nShoshadiganlar','2026-01-09 09:06:37',NULL),
(345,47,7,7,NULL,'Mijozlarimizning samimiy fikri!','Reels','2026-01-12','Mijozlar uy xairid qilgandagi taasurotini so\'rash','2026-01-09 09:58:47','2026-01-12 10:25:37'),
(346,47,7,7,NULL,'Vaqtingizni ham, naqtingizni ham tejang!','Reels','2026-01-14','Tuman va uzoq joylarda turadigan mijozlar uchun video','2026-01-09 10:20:35','2026-01-12 10:25:43'),
(347,47,7,7,NULL,'Qizg‘in qurilish jarayonidan qaynoq lavhalar!','Reels','2026-01-17','Qurilishdan video','2026-01-09 10:24:23','2026-01-12 10:25:50'),
(348,47,7,7,NULL,'DAMS aksiyasi — endi yanada qulay shartlarda!','Reels','2026-01-21','Damas aksiya shartlari','2026-01-09 10:27:00','2026-01-12 10:25:57'),
(353,47,7,7,NULL,'Loyhamizning suniy intelekt yoramida ko\'rinishi!','Animation','2026-01-25','AI da animatsya qilish','2026-01-09 11:18:23','2026-01-12 10:26:03'),
(354,47,7,7,NULL,'Kattakurgan city boshlang\'ich to\'lovsiz xonadon xarid qiling','Animation','2026-01-27','Moushn post','2026-01-09 11:19:38','2026-01-12 10:26:08'),
(355,47,7,NULL,NULL,'Kelajakdagi biznesingiz uchun eng to\'g\'ri sarmoya!','Carousel','2026-01-30','Do\'konlar haqida karusel post','2026-01-12 10:25:27',NULL),
(356,46,7,NULL,NULL,'Orzudan koreagacha 6 oyda','Animation','2026-01-30','Bola o\'qishini boshlaganidan koreaga uchib ketayotgani','2026-01-12 11:26:23',NULL),
(357,46,7,NULL,NULL,'Korea yangi imkoniyatlar mamalakati','Animation','2026-02-02','Videoni boshida korea haqida ilhomlantiruvchi ma\'lumot keyin mega foundation haqida','2026-01-12 11:45:00',NULL),
(358,46,7,NULL,NULL,'Bir qaror hayotni o\'zgartiradi','Post','2026-02-05','Har qanda katta o\'zgarish birinchi qadamdan boshlanadi.','2026-01-12 11:48:42',NULL);
/*!40000 ALTER TABLE `content_plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20250619112755','2025-11-09 23:09:36',69),
('DoctrineMigrations\\Version20251023091519','2025-11-09 23:09:36',102),
('DoctrineMigrations\\Version20251023092137','2025-11-09 23:09:36',153),
('DoctrineMigrations\\Version20251023103706','2025-11-09 23:09:36',9),
('DoctrineMigrations\\Version20251023121457','2025-11-09 23:09:36',7);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_object`
--

DROP TABLE IF EXISTS `media_object`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_object`
--

LOCK TABLES `media_object` WRITE;
/*!40000 ALTER TABLE `media_object` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_object` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `executor_id` int(11) NOT NULL,
  `created_by_id` int(11) NOT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `deleted_by_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2FB3D0EE8ABD09BB` (`executor_id`),
  KEY `IDX_2FB3D0EEB03A8386` (`created_by_id`),
  KEY `IDX_2FB3D0EE896DBBDE` (`updated_by_id`),
  KEY `IDX_2FB3D0EEC76F1F52` (`deleted_by_id`),
  CONSTRAINT `FK_2FB3D0EE896DBBDE` FOREIGN KEY (`updated_by_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_2FB3D0EE8ABD09BB` FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_2FB3D0EEB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_2FB3D0EEC76F1F52` FOREIGN KEY (`deleted_by_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` VALUES
(3,4,4,NULL,NULL,'Test1','998 (22) 222 - 22 - 22','2025-11-10 01:23:13',NULL),
(4,5,5,NULL,NULL,'ff','998 (91) 523 - 59 - 79','2025-11-14 14:09:00',NULL),
(5,5,5,NULL,NULL,'Demir','998 (00) 000 - 00 - 00','2025-11-14 14:11:39',NULL),
(6,5,5,NULL,NULL,'Sogda med','998 (00) 000 - 00 - 00','2025-11-14 14:13:25',NULL),
(7,6,6,6,NULL,'Nextform.uz','998 (99) 255 - 71 - 71','2025-11-15 06:07:54','2025-11-15 06:46:46'),
(8,6,6,6,NULL,'Memar','998 (77) 321 - 06 - 06','2025-11-15 06:08:07','2025-11-15 07:07:09'),
(9,9,9,NULL,NULL,'Adham aka yangi domlar','998 (94) 289 - 09 - 97','2025-11-15 14:36:55',NULL),
(10,5,5,NULL,NULL,'MOBETCO','998 (00) 000 - 00 - 00','2025-11-17 06:48:55',NULL),
(11,10,10,NULL,NULL,'Test Project','998 (22) 222 - 22 - 22','2025-11-17 07:13:54',NULL),
(12,7,7,7,7,'STB beton','998 (95) 787 - 60 - 70','2025-11-18 06:21:09','2025-12-03 12:10:21'),
(13,7,7,NULL,NULL,'Kattakurgan City','998 (99) 899 - 09 - 89','2025-11-18 11:59:37',NULL),
(14,11,11,NULL,NULL,'MacCaldo','998 (90) 450 - 09 - 69','2025-11-24 15:16:45',NULL),
(15,12,12,12,NULL,'Sabfir zoloto','998 (97) 896 - 99 - 99','2025-11-25 06:30:04','2025-11-25 07:58:47'),
(16,12,12,NULL,NULL,'Meros Hospital','998 (70) 127 - 07 - 28','2025-11-27 07:06:28',NULL),
(17,1,1,NULL,NULL,'Biznes Kapsula Klubi','998 (99) 166 - 01 - 23','2025-11-27 11:07:10',NULL),
(18,5,5,NULL,NULL,'Biznes Kapsula Klubi','998 (99) 116 - 01 - 23','2025-11-27 11:48:33',NULL),
(19,12,12,NULL,NULL,'Rizayev','998 (97) 919 - 44 - 48','2025-11-28 07:21:54',NULL),
(20,7,7,NULL,NULL,'Mega Foundation','998 (98) 998 - 64 - 21','2025-12-02 10:48:45',NULL),
(21,5,5,NULL,NULL,'hfghfg','998 (67) 675 - 75 - 75','2025-12-03 05:03:59',NULL),
(22,7,7,NULL,NULL,'Sogda Med','998 (97) 287 - 70 - 77','2025-12-03 08:56:22',NULL),
(23,5,5,NULL,NULL,'Onur','998 (66) 666 - 66 - 66','2025-12-03 12:26:42',NULL),
(24,7,7,NULL,NULL,'Osh Qand','998 (91) 526 - 16 - 00','2025-12-03 13:26:22',NULL),
(25,7,7,7,NULL,'Millennium Stone','998 (93) 353 - 88 - 00','2025-12-04 13:22:05','2025-12-04 13:22:27'),
(26,5,5,NULL,NULL,'FIRDAVSIY PREMIUM ','998 (77) 012 - 22 - 22','2025-12-08 07:15:45',NULL),
(27,11,11,11,NULL,'hamd','998 (97) 920 - 88 - 88','2025-12-09 06:39:48','2025-12-09 07:16:54'),
(28,11,11,11,NULL,'promo lux','998 (93) 722 - 72 - 82','2025-12-09 07:18:14','2025-12-09 07:36:45'),
(29,7,7,NULL,NULL,'Ice berg','998 (97) 391 - 99 - 91','2025-12-09 07:23:40',NULL),
(30,11,11,11,NULL,'memar','998 (88) 211 - 22 - 33','2025-12-09 07:46:13','2026-01-06 10:20:59'),
(31,11,11,NULL,NULL,'zarafshon group','998 (98) 210 - 73 - 33','2025-12-09 09:17:54',NULL),
(32,7,7,7,NULL,'Oshqand new','998 (91) 526 - 16 - 00','2025-12-09 13:23:29','2025-12-09 13:23:40'),
(33,8,8,NULL,NULL,'Bestten','998 (94) 692 - 21 - 11','2025-12-10 12:02:58',NULL),
(34,7,7,NULL,NULL,'Shoxona Sendvich','998 (91) 559 - 99 - 10','2025-12-11 13:09:01',NULL),
(35,13,13,NULL,13,'KANSMARKET ','998 (99) 891 - 22 - 00','2025-12-15 02:01:42','2025-12-15 02:05:28'),
(36,13,13,NULL,13,'KANSMARKET ','998 (99) 891 - 22 - 00','2025-12-15 02:05:12','2025-12-15 02:05:30'),
(37,13,13,13,NULL,'KANSMARKET ','998 (99) 891 - 22 - 00','2025-12-15 02:07:22','2025-12-15 18:14:23'),
(38,13,13,NULL,13,'KANSMARKET ','998 (97) 891 - 22 - 00','2025-12-15 08:16:44','2025-12-15 08:17:30'),
(39,13,13,NULL,13,'KANSMARKET','998 (97) 891 - 22 - 00','2025-12-15 17:33:46','2025-12-15 17:33:51'),
(40,7,7,NULL,7,'Kattakurgan City New','998 (99) 889 - 09 - 89','2025-12-17 08:43:37','2026-01-12 10:19:29'),
(41,12,12,NULL,NULL,'Meros Hospitale','998 (70) 127 - 02 - 28','2026-01-03 09:57:37',NULL),
(42,12,12,12,NULL,'Dr.Rizayev 2','998 (97) 919 - 44 - 48','2026-01-03 11:05:12','2026-01-03 13:20:58'),
(43,12,12,12,NULL,'Garden avenue','998 (95) 602 - 99 - 99','2026-01-06 06:10:19','2026-01-06 07:47:19'),
(44,11,11,NULL,NULL,'samarqand tarixi','998 (90) 450 - 09 - 69','2026-01-06 10:21:30',NULL),
(45,11,11,NULL,NULL,'bobeka','998 (97) 921 - 22 - 21','2026-01-07 07:06:27',NULL),
(46,7,7,NULL,NULL,'Mega Foundation New','998 (98) 998 - 64 - 21','2026-01-09 06:23:58',NULL),
(47,7,7,7,NULL,'Kattakurgan City New','998 (99) 899 - 09 - 89','2026-01-09 06:27:34','2026-01-12 10:19:40'),
(48,7,7,NULL,NULL,'Oshqand New','998 (91) 526 - 16 - 00','2026-01-12 11:49:38',NULL);
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `updated_by_id` int(11) DEFAULT NULL,
  `deleted_by_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `given_name` varchar(255) NOT NULL,
  `family_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8D93D649896DBBDE` (`updated_by_id`),
  KEY `IDX_8D93D649C76F1F52` (`deleted_by_id`),
  CONSTRAINT `FK_8D93D649896DBBDE` FOREIGN KEY (`updated_by_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_8D93D649C76F1F52` FOREIGN KEY (`deleted_by_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,NULL,NULL,'khagencyai@gmail.com','$2y$13$kcv5GbaD7oZnAvhnJsQeOubbSUYvqLIAq1mNO0OOMwov2GjGWivNO','a:1:{i:0;s:10:\"ROLE_ADMIN\";}','2026-01-12 13:27:42','2025-11-10 00:06:17',NULL,'Elchin',NULL),
(3,NULL,1,'hightimcg@gmailc.om','$2y$13$rACog8xpYjjQgzuL/wJpzORlwAdT.goqiW2egEJ4zJSiJzto6/Wz2','a:0:{}','2025-11-10 01:21:45','2025-11-10 01:21:55','2025-11-10 01:21:55','Temur','Fayzullayev'),
(4,NULL,NULL,'hightimcg@gmail.com','$2y$13$g/mgd5Xug3xBIjwfJ0Dec.nS6jXfkbtYRBe/HhMIH1V4ZhxBcEJmC','a:0:{}','2025-11-10 01:22:56',NULL,NULL,'Temur','Fayzullayev'),
(5,NULL,NULL,'rasulovelchinbek01@gmail.com','$2y$13$iM.kjzLcDB5SQcFw1Q039e4GHH5.mHfhHQgj6XGU/4pMUzWX1s7W.','a:0:{}','2025-12-16 14:15:11',NULL,NULL,'Elchin','Rasulov'),
(6,NULL,NULL,'Zaminlife1@gmail.com','$2y$13$c65oivK8z1e3lB3Gw09SKuXgA/pEQvZgONvMLA3niCpU/ff9.MKJK','a:0:{}','2025-11-15 06:07:28',NULL,NULL,'Ikrom ','Ro\'zimurodov'),
(7,NULL,NULL,'razzoqovshaxram2@gmail.com','$2y$13$.WJHVtKzDMf526Wb70L69OuCGcm/Oz2Bf3o8O.FEfbOSqGSp/X7Sa','a:0:{}','2026-01-12 10:19:14',NULL,NULL,'Shaxram ','Razzoqov'),
(8,NULL,NULL,'jassqanoatov@gmail.com','$2y$13$PGy/A7RFHbif10x7akURkeF5sSmmgIcj0SyVg4q4tCZs5t3avwDqG','a:0:{}','2026-01-06 08:56:51',NULL,NULL,'Jasurbek ','Qanoatov'),
(9,NULL,NULL,'karimovmenejer@gmail.com','$2y$13$zWIReR.D4HH0Y.XYhoIXterr4mvovQjbKmibHBBiRwxIZrPcnPx2W','a:0:{}','2025-11-15 14:35:19',NULL,NULL,'O\'tkir','Karimov'),
(10,NULL,NULL,'test@test.test','$2y$13$z3KdLvvx/hrZTWk6Zzh.2.LYi9uqfMFx4N6KpB5CEm/VEzAIODhhe','a:0:{}','2025-12-16 09:40:19',NULL,NULL,'TestName','Tast'),
(11,NULL,NULL,'diyorjoxonov@gmail.com','$2y$13$de.jtBGKuhAESgZabxSST.MBKkvNMNixgIEN/cEYTM6jPA1Y7S24S','a:0:{}','2026-01-13 05:39:21',NULL,NULL,'Diyor ','Joxonov'),
(12,NULL,NULL,'azizjaxonov14@gmail.com','$2y$13$Z/pH4c8jAaceBVEI4GvSQev0KwjgCJws8oiS1Myuifzh7Efyuku7q','a:0:{}','2026-01-08 08:52:39',NULL,NULL,'Azizbek','Jaxonov'),
(13,1,1,'rukhshonkanarkulova@gmail.com','$2y$13$VdH72IqAzhE1ojAEkiZ7VunG6hImhdJQTBp03cRUBHGrcv/r4Y.Gm','a:0:{}','2025-12-15 18:13:54','2026-01-12 13:29:41','2026-01-12 13:29:41','Rukhshona ','Narkulova'),
(14,NULL,NULL,'rukhshonanarkulova@gmail.com','$2y$13$YHa717L9S198JuR0Lcq.T..c05iMCMPu4MA3enOKBwznYg1GRVySS','a:0:{}','2026-01-12 13:30:29',NULL,NULL,'Rukhshona','Narkulova');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-01-13  7:20:34
