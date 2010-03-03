CREATE TABLE `!prefix_!logfile` (

  `logid` bigint(20) NOT NULL auto_increment,

  `sessionid` varchar(34) NOT NULL default '',

  `logdate` datetime NOT NULL default '0000-00-00 00:00:00',

  `type` int(11) NOT NULL default '0',

  `subtype` int(11) NOT NULL default '0',

  `descr` varchar(255) NOT NULL default '',

  `status` int(11) NOT NULL default '0',

  PRIMARY KEY  (`logid`),

  KEY `sessionid` (`sessionid`),

  KEY `status` (`status`),

  KEY `type` (`type`,`subtype`)

);

CREATE TABLE `!prefix_!artist` (

  `id` int(10) unsigned NOT NULL auto_increment,

  `src` varchar(32) NOT NULL default '',

  `name` varchar(200) NOT NULL default '',

  `preferred_name_id` int(10) unsigned NOT NULL default '0',

  `sounds` varchar(60) NOT NULL default '',

  PRIMARY KEY  (`id`),

  KEY `name_sounds` (`name`,`sounds`),

  KEY `parentid` (`preferred_name_id`),

  KEY `sound_dx` (`sounds`),

  KEY `parentid_sounds_dx` (`preferred_name_id`,`sounds`),

  KEY `namedx` (`name`)

);

CREATE TABLE `!prefix_!collection` (

  `collectionid` bigint(20) NOT NULL auto_increment,

  `name` varchar(255) NOT NULL default '',

  `host` varchar(255) NOT NULL default 'local',

  `active` int(11) NOT NULL default '1',

  `sammlu!prefix_!ort` varchar(155) NOT NULL default '',

  `descr` varchar(255) default NULL,

  `email` varchar(255) default NULL,

  `url` varchar(255) default NULL,

  `tabelle` varchar(15) NOT NULL default '',

  `classname` varchar(25) NOT NULL default '',

  `indexfeld` varchar(15) NOT NULL default '',

  `schlagwortfeld` varchar(15) NOT NULL default '',

  `getbild` text NOT NULL,

  `include` varchar(128) NOT NULL default '',

  PRIMARY KEY  (`collectionid`),

  KEY `active` (`active`)

);

CREATE TABLE `!prefix_!config` (

  `name` varchar(40) NOT NULL default '',

  `val` varchar(120) NOT NULL default '',

  `descr` varchar(120) default NULL

);

CREATE TABLE `!prefix_!dating` (

  `collectionid` bigint(20) NOT NULL default '0',

  `imageid` bigint(20) NOT NULL default '0',

  `from` bigint(20) NOT NULL default '0',

  `to` bigint(20) NOT NULL default '0',

  `metaid` int(12) NOT NULL default '0',

  KEY `collectionid` (`collectionid`,`imageid`),

  KEY `from` (`from`),

  KEY `to` (`to`),

  KEY `fromto` (`from`,`to`)

);

CREATE TABLE `!prefix_!dating_rules` (

  `seq` int(11) NOT NULL default '0',

  `type` set('match','replace') NOT NULL default 'match',

  `regexp` text NOT NULL,

  `from` text NOT NULL,

  `to` text NOT NULL,

  `descr` text NOT NULL,

  KEY `seq` (`seq`),

  KEY `type` (`type`)

);

CREATE TABLE `!prefix_!group` (

  `id` int(11) NOT NULL default '0',
  
  `name` text NOT NULL,
  
  `parentid` int(11) NOT NULL default '0',
  
  `owner` text NOT NULL,
  
  PRIMARY KEY  (`id`)
  
);

CREATE TABLE `!prefix_!img` (

  `collectionid` bigint(20) NOT NULL default '0',

  `imageid` bigint(20) NOT NULL default '0',

  `img_baseid` bigint(20) NOT NULL default '0',

  `filename` varchar(255) NOT NULL default '',

  `width` int(11) default NULL,

  `height` int(11) default NULL,

  `xres` int(11) default NULL,

  `yres` int(11) default NULL,

  `size` int(11) default NULL,

  `magick` varchar(20) default NULL,

  `insert_date` datetime NOT NULL default '0000-00-00 00:00:00',

  `modify_date` datetime NOT NULL default '0000-00-00 00:00:00',

  PRIMARY KEY  (`collectionid`,`imageid`),

  KEY `filename_2` (`filename`),

  KEY `img_baseid` (`img_baseid`)

);

CREATE TABLE `!prefix_!img_base` (

  `img_baseid` bigint(20) NOT NULL auto_increment,

  `collectionid` bigint(20) NOT NULL default '0',

  `base` varchar(255) NOT NULL default '',

  `getbild` text NOT NULL,

  `host` varchar(80) NOT NULL default '',

  PRIMARY KEY  (`img_baseid`),

  KEY `sammlungid` (`collectionid`)

);

CREATE TABLE `!prefix_!img_group` (  

  `groupid` int(11) NOT NULL default '0',
  
  `collectionid` int(11) NOT NULL default '0',  

  `imageid` int(11) NOT NULL default '0',

  PRIMARY KEY  (`groupid`,`collectionid`,`imageid`)

);

CREATE TABLE `!prefix_!location` (

  `id` int(10) unsigned NOT NULL auto_increment,
  
  `src` varchar(32) NOT NULL default '',
  
  `source_id` varchar(15) NOT NULL default '0',
  
  `parent_source_id` varchar(15) NOT NULL default '0',
  
  `location` varchar(200) NOT NULL default '',
  
  `loc_type` varchar(60) NOT NULL default '',
  
  `hierarchy` varchar(255) NOT NULL default '',
  
  `sounds` varchar(60) NOT NULL default '',
  
  PRIMARY KEY  (`id`),
  
  UNIQUE KEY `unique_src_source_id` (`src`,`source_id`),
  
  KEY `location_sounds` (`location`,`sounds`)
);

CREATE TABLE `!prefix_!meta` (

  `collectionid` bigint(20) NOT NULL default '0',

  `imageid` bigint(20) NOT NULL default '0',

  `type` varchar(64) NOT NULL default 'image',

  `status` set('new','edited','reviewed') NOT NULL default '',

  `addition` varchar(255) default NULL,

  `title` varchar(200) default NULL,

  `dating` varchar(80) default NULL,

  `material` varchar(200) default NULL,

  `technique` varchar(200) default NULL,

  `format` varchar(40) default NULL,

  `institution` varchar(255) NOT NULL default '',

  `literature` varchar(200) default NULL,

  `page` varchar(10) NOT NULL default '',

  `figure` varchar(10) NOT NULL default '',

  `table` varchar(10) NOT NULL default '',

  `isbn` varchar(15) NOT NULL default '',

  `keyword` varchar(200) default NULL,

  `insert_date` datetime NOT NULL default '0000-00-00 00:00:00',

  `modify_date` datetime NOT NULL default '0000-00-00 00:00:00',

  `name1id` int(10) unsigned NOT NULL default '0',

  `name2id` int(10) unsigned NOT NULL default '0',

  `locationid` int(10) unsigned NOT NULL default '0',

  `exp_prometheus` tinyint(1) NOT NULL default '0',

  `exp_sid` tinyint(1) NOT NULL default '0',

  `exp_unimedia` tinyint(1) NOT NULL default '0',

  `commentary` text NOT NULL,

  `metacreator` varchar(120) NOT NULL default '',

  `metaeditor` varchar(120) NOT NULL default '',

  `imagerights` varchar(255) NOT NULL default '',

  `name1` varchar(200) default NULL,

  `name2` varchar(200) default NULL,

  `location` varchar(200) default NULL,

  `locationsounds` varchar(60) default NULL,

  `name1sounds` varchar(60) default NULL,

  `name2sounds` varchar(60) default NULL,

  `id` int(12) NOT NULL auto_increment,

  PRIMARY KEY  (`collectionid`,`imageid`),

  UNIQUE KEY `id` (`id`),

  KEY `titel` (`title`),

  KEY `isbn` (`isbn`),

  KEY `institution` (`institution`),

  KEY `stadt_inst` (`institution`),

  KEY `status` (`status`)

);

CREATE TABLE `!prefix_!type` (

  `name` varchar(64) NOT NULL default '',

  `print_name` varchar(64) NOT NULL default '',

  `detail` varchar(128) NOT NULL default 'default_detail.tpl',

  `edit` varchar(128) NOT NULL default 'default_edit.tpl',

  `list` varchar(128) NOT NULL default 'default_list.tpl',

  `list_short` varchar(128) NOT NULL default 'default_list_short.tpl',

  `grid` varchar(128) NOT NULL default 'default_grid.tpl',

  UNIQUE KEY `name` (`name`)

);

CREATE TABLE `!prefix_!session` (

  `sessionid` varchar(34) NOT NULL default '',

  `start` datetime NOT NULL default '0000-00-00 00:00:00',

  `end` datetime NOT NULL default '0000-00-00 00:00:00',

  `lastaccess` datetime NOT NULL default '0000-00-00 00:00:00',

  `ip` bigint(20) NOT NULL default '0',

  `counter` bigint(20) NOT NULL default '0',

  `active` tinyint(4) NOT NULL default '1',

  `session_data` blob NOT NULL,

  PRIMARY KEY  (`sessionid`),

  KEY `ip` (`ip`),

  KEY `start` (`start`),

  KEY `lastaccess` (`lastaccess`),

  KEY `active` (`active`),

  KEY `end` (`end`)

);

CREATE TABLE `!prefix_!user_auth` (

  `userid` varchar(100) NOT NULL,
  
  `authtype` enum('static','ldap','imap') NOT NULL default 'static',
  
  `admin` tinyint(4) NOT NULL default '0',
  
  `editor` tinyint(4) NOT NULL default '0',
  
  `addimages` tinyint(4) NOT NULL default '0',
  
  `usegroups` tinyint(4) NOT NULL default '1',
  
  `usefolders` tinyint(4) NOT NULL default '1',
  
  `active` tinyint(4) NOT NULL default '0',
  
  PRIMARY KEY  (`userid`)
  
);


CREATE TABLE `!prefix_!user_passwd` (

  `userid` varchar(100) NOT NULL,
  
  `passwd` varchar(100) NOT NULL,
  
  PRIMARY KEY  (`userid`)
  
);


INSERT INTO `!prefix_!user_passwd` VALUES ('admin', '#ADMINPW#');

INSERT INTO `!prefix_!user_passwd` VALUES ('#NAME#', '#USERPW#');


INSERT INTO `!prefix_!user_auth` VALUES ('admin', 'static', 1, 1, 1, 1, 1, 1);

INSERT INTO `!prefix_!user_auth` VALUES ('#NAME#', 'static', 1, 1, 1, 1, 1, 1);



INSERT INTO `!prefix_!type` VALUES ('image', 'Bild', 'default_detail.tpl', 'default_edit.tpl', 'default_list.tpl',  'default_list_short.tpl', 'default_grid.tpl');

INSERT INTO `!prefix_!type` VALUES ('architecture', 'Architektur', 'default_detail.tpl', 'default_edit.tpl', 'default_list.tpl', 'default_list_short.tpl', 'default_grid.tpl');


INSERT INTO `!prefix_!location` VALUES (1, 'Getty TGN', '1000003', '7029392', 'Europe', '29000/continent', 'World', '.ERP.');

INSERT INTO `!prefix_!location` VALUES (2, 'Getty TGN', '1000080', '1000003', 'Italia', '81010/nation', 'Europe', '.ITL.');

INSERT INTO `!prefix_!location` VALUES (3, 'Getty TGN', '1003460', '7009760', 'Grosseto', '81161/province', 'Toscana | Italia | Europe', '.GRST.');

INSERT INTO `!prefix_!location` VALUES (4, 'Getty TGN', '1003473', '7009760', 'Massa-Carrara', '81161/province', 'Toscana | Italia | Europe', '.MSKR.');

INSERT INTO `!prefix_!location` VALUES (5, 'Getty TGN', '1006715', '7003164', 'Capraia, Isola di', '21471/island', 'Livorno province | Toscana | Italia | Europe', '.KPR.ISL.D.');

INSERT INTO `!prefix_!location` VALUES (6, 'Getty TGN', '1007187', '1003460', 'Giannutri, Isola di', '21471/island', 'Grosseto province | Toscana | Italia | Europe', '.GNTR.ISL.D.');

INSERT INTO `!prefix_!location` VALUES (7, 'Getty TGN', '1007191', '1003460', 'Giglio, Isola del', '21471/island', 'Grosseto province | Toscana | Italia | Europe', '.GL.ISL.DL.');

INSERT INTO `!prefix_!location` VALUES (8, 'Getty TGN', '1008204', '7003164', 'Montecristo, Isola di', '83210/deserted settlement', 'Livorno province | Toscana | Italia | Europe', '.MNTC.ISL.D.');

INSERT INTO `!prefix_!location` VALUES (9, 'Getty TGN', '1012389', '7006197', 'Ripalti, Punta dei', '21464/point', 'Elba, Isola d'' | Livorno province | Toscana | Italia | Europe', '.RPLT.PNT.D.');

INSERT INTO `!prefix_!location` VALUES (10, 'Getty TGN', '1043080', '7003167', 'Abetone', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.ABTN.');

INSERT INTO `!prefix_!location` VALUES (11, 'Getty TGN', '1043117', '7003167', 'Agliana', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.AGLN.');

INSERT INTO `!prefix_!location` VALUES (12, 'Getty TGN', '1043230', '7003164', 'Antignano', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.ANTG.');

INSERT INTO `!prefix_!location` VALUES (13, 'Getty TGN', '1043261', '1003460', 'Arcille', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.ARCL.');

INSERT INTO `!prefix_!location` VALUES (14, 'Getty TGN', '1043270', '7003164', 'Ardenza', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.ARDN.');

INSERT INTO `!prefix_!location` VALUES (15, 'Getty TGN', '1043289', '7003166', 'Arnaccio', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.ARNK.');

INSERT INTO `!prefix_!location` VALUES (16, 'Getty TGN', '1043291', '7003165', 'Arni', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.ARN.');

INSERT INTO `!prefix_!location` VALUES (17, 'Getty TGN', '1043360', '7003162', 'Badia Tedalda', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.BD.TDLD.');

INSERT INTO `!prefix_!location` VALUES (18, 'Getty TGN', '1043373', '7003168', 'Bagno Vignoni', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.BGN.VGN.');

INSERT INTO `!prefix_!location` VALUES (19, 'Getty TGN', '1043381', '1003473', 'Bagnone', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.BGN.');

INSERT INTO `!prefix_!location` VALUES (20, 'Getty TGN', '1043404', '1003473', 'Barbarasco', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.BRBR.');

INSERT INTO `!prefix_!location` VALUES (21, 'Getty TGN', '1043406', '7003163', 'Barberino di Mugello', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.BRBR.D.MGL.');

INSERT INTO `!prefix_!location` VALUES (22, 'Getty TGN', '1043500', '7003164', 'Bibbona', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.BBN.');

INSERT INTO `!prefix_!location` VALUES (23, 'Getty TGN', '1043506', '7003166', 'Bientina', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.BNTN.');

INSERT INTO `!prefix_!location` VALUES (24, 'Getty TGN', '1043665', '7003162', 'Bucine', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.BCN.');

INSERT INTO `!prefix_!location` VALUES (25, 'Getty TGN', '1043693', '7003166', 'Buti', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.BT.');

INSERT INTO `!prefix_!location` VALUES (26, 'Getty TGN', '1043728', '7003166', 'Calcinaia', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.KLCN.');

INSERT INTO `!prefix_!location` VALUES (27, 'Getty TGN', '1043776', '7003168', 'Campiglia dei Fosci', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KMPG.D.FSC.');

INSERT INTO `!prefix_!location` VALUES (28, 'Getty TGN', '1043808', '7003162', 'Camucia', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.KMC.');

INSERT INTO `!prefix_!location` VALUES (29, 'Getty TGN', '1043843', '7003166', 'Capannoli', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.KPNL.');

INSERT INTO `!prefix_!location` VALUES (30, 'Getty TGN', '1043852', '7006197', 'Capoliveri', '83002/inhabited place', 'Elba, Isola d'' | Livorno province | Toscana | Italia | Europe', '.KPLV.');

INSERT INTO `!prefix_!location` VALUES (31, 'Getty TGN', '1043854', '7003167', 'Capostrada', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.KPST.');

INSERT INTO `!prefix_!location` VALUES (32, 'Getty TGN', '1043955', '1003473', 'Casola in Lunigiana', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.KZL.IN.LNGN.');

INSERT INTO `!prefix_!location` VALUES (33, 'Getty TGN', '1043975', '7003164', 'Castagneto Carducci', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.KSTG.KRDK.');

INSERT INTO `!prefix_!location` VALUES (34, 'Getty TGN', '1043986', '7003168', 'Castel San Gimignano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KSTL.SN.GMGN.');

INSERT INTO `!prefix_!location` VALUES (35, 'Getty TGN', '1043996', '1003460', 'Castel del Piano', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.KSTL.DL.PN.');

INSERT INTO `!prefix_!location` VALUES (36, 'Getty TGN', '1044018', '1003460', 'Castell''Azzara', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.KSTL.');

INSERT INTO `!prefix_!location` VALUES (37, 'Getty TGN', '1044056', '7003168', 'Castelnuovo dell''Abate', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KSTL.DLBT.');

INSERT INTO `!prefix_!location` VALUES (38, 'Getty TGN', '1044058', '7003165', 'Castelnuovo di Garfagnana', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.KSTL.D.KRFG.');

INSERT INTO `!prefix_!location` VALUES (39, 'Getty TGN', '1044060', '7003166', 'Castelnuovo di Val di Cecina', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.KSTL.D.VL.D.CN.');

INSERT INTO `!prefix_!location` VALUES (40, 'Getty TGN', '1044073', '7003162', 'Castiglion Fibocchi', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.KSTG.FBK.');

INSERT INTO `!prefix_!location` VALUES (41, 'Getty TGN', '1044085', '1003460', 'Castiglione della Pescaia', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.KSTG.DL.PSK.');

INSERT INTO `!prefix_!location` VALUES (42, 'Getty TGN', '1044126', '7003164', 'Cecina', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.CN.');

INSERT INTO `!prefix_!location` VALUES (43, 'Getty TGN', '1044204', '7003166', 'Chianni', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.CHN.');

INSERT INTO `!prefix_!location` VALUES (44, 'Getty TGN', '1044250', '1003460', 'Cinigiano', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.CNGN.');

INSERT INTO `!prefix_!location` VALUES (45, 'Getty TGN', '1044257', '7003167', 'Cireglio', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.CRGL.');

INSERT INTO `!prefix_!location` VALUES (46, 'Getty TGN', '1044289', '1003460', 'Civitella Marittima', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.CVTL.MRTM.');

INSERT INTO `!prefix_!location` VALUES (47, 'Getty TGN', '1044293', '7003162', 'Civitella in Val di Chiana', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.CVTL.IN.VL.D.CHN.');

INSERT INTO `!prefix_!location` VALUES (48, 'Getty TGN', '1044329', '7003164', 'Collesalvetti', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.KLZL.');

INSERT INTO `!prefix_!location` VALUES (49, 'Getty TGN', '1044342', '1003473', 'Colonnata', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.KLNT.');

INSERT INTO `!prefix_!location` VALUES (50, 'Getty TGN', '1044363', '7003163', 'Consuma', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KNSM.');

INSERT INTO `!prefix_!location` VALUES (51, 'Getty TGN', '1044378', '7003165', 'Coreglia Antelminelli', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.KRGL.ANTL.');

INSERT INTO `!prefix_!location` VALUES (52, 'Getty TGN', '1044419', '7003163', 'Covigliaio', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KVGL.');

INSERT INTO `!prefix_!location` VALUES (53, 'Getty TGN', '1044483', '7003163', 'Dicomano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.DKMN.');

INSERT INTO `!prefix_!location` VALUES (54, 'Getty TGN', '1044532', '1003473', 'Equi Terme', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.EK.TRM.');

INSERT INTO `!prefix_!location` VALUES (55, 'Getty TGN', '1044568', '7003166', 'Fauglia', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.FGL.');

INSERT INTO `!prefix_!location` VALUES (56, 'Getty TGN', '1044626', '1003473', 'Fivizzano', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.FVZN.');

INSERT INTO `!prefix_!location` VALUES (57, 'Getty TGN', '1044641', '1003460', 'Follonica', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.FLNK.');

INSERT INTO `!prefix_!location` VALUES (58, 'Getty TGN', '1044654', '1003460', 'Fonte Blanda', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.FNT.BLND.');

INSERT INTO `!prefix_!location` VALUES (59, 'Getty TGN', '1044669', '7003163', 'Fornacelle', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.FRNC.');

INSERT INTO `!prefix_!location` VALUES (60, 'Getty TGN', '1044679', '7003165', 'Forte dei Marmi', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.FRT.D.MRM.');

INSERT INTO `!prefix_!location` VALUES (61, 'Getty TGN', '1044682', '1003473', 'Fosdinovo', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.FSDN.');

INSERT INTO `!prefix_!location` VALUES (62, 'Getty TGN', '1044730', '7003168', 'Gaiole in Chianti', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KL.IN.CHNT.');

INSERT INTO `!prefix_!location` VALUES (63, 'Getty TGN', '1044749', '7003163', 'Gambassi Terme', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KMBS.TRM.');

INSERT INTO `!prefix_!location` VALUES (64, 'Getty TGN', '1044775', '7003167', 'Gavinana', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.KVN.');

INSERT INTO `!prefix_!location` VALUES (65, 'Getty TGN', '1044778', '1003460', 'Gavorrano', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.KVRN.');

INSERT INTO `!prefix_!location` VALUES (66, 'Getty TGN', '1044816', '1007191', 'Giglio Porto', '83002/inhabited place', 'Giglio, Isola del | Grosseto province | Toscana | Italia | Europe', '.GL.PRT.');

INSERT INTO `!prefix_!location` VALUES (67, 'Getty TGN', '1044983', '7003167', 'La Lima', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.L.LM.');

INSERT INTO `!prefix_!location` VALUES (68, 'Getty TGN', '1045019', '7003166', 'Larderello', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.LRDR.');

INSERT INTO `!prefix_!location` VALUES (69, 'Getty TGN', '1045020', '7003166', 'Lari', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.LR.');

INSERT INTO `!prefix_!location` VALUES (70, 'Getty TGN', '1045050', '7003165', 'Le Focette', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.L.FCT.');

INSERT INTO `!prefix_!location` VALUES (71, 'Getty TGN', '1045051', '7003167', 'Piastre', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.PSTR.');

INSERT INTO `!prefix_!location` VALUES (72, 'Getty TGN', '1045082', '1003473', 'Licciana Nardi', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.LKN.NRD.');

INSERT INTO `!prefix_!location` VALUES (73, 'Getty TGN', '1045085', '7003165', 'Lido di Camaiore', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.LD.D.KMR.');

INSERT INTO `!prefix_!location` VALUES (74, 'Getty TGN', '1045182', '1003460', 'Magliano in Toscana', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.MGLN.IN.TSKN.');

INSERT INTO `!prefix_!location` VALUES (75, 'Getty TGN', '1045211', '1003460', 'Manciano', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.MNCN.');

INSERT INTO `!prefix_!location` VALUES (76, 'Getty TGN', '1045242', '7006197', 'Marciana', '83002/inhabited place', 'Elba, Isola d'' | Livorno province | Toscana | Italia | Europe', '.MRCN.');

INSERT INTO `!prefix_!location` VALUES (77, 'Getty TGN', '1045243', '7006197', 'Marciana Marina', '83002/inhabited place', 'Elba, Isola d'' | Livorno province | Toscana | Italia | Europe', '.MRCN.MRN.');

INSERT INTO `!prefix_!location` VALUES (78, 'Getty TGN', '1045256', '7006197', 'Marina di Campo', '83002/inhabited place', 'Elba, Isola d'' | Livorno province | Toscana | Italia | Europe', '.MRN.D.KMP.');

INSERT INTO `!prefix_!location` VALUES (79, 'Getty TGN', '1045258', '1003473', 'Marina di Carrara', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.MRN.D.KRR.');

INSERT INTO `!prefix_!location` VALUES (80, 'Getty TGN', '1045259', '7003164', 'Marina di Cecina', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.MRN.D.CN.');

INSERT INTO `!prefix_!location` VALUES (81, 'Getty TGN', '1045261', '1003460', 'Marina di Grosseto', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.MRN.D.GRST.');

INSERT INTO `!prefix_!location` VALUES (82, 'Getty TGN', '1045262', '1003473', 'Marina di Massa', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.MRN.D.MS.');

INSERT INTO `!prefix_!location` VALUES (83, 'Getty TGN', '1045265', '7003165', 'Marina di Pietrasanta', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.MRN.D.PTRZ.');

INSERT INTO `!prefix_!location` VALUES (84, 'Getty TGN', '1045266', '7003166', 'Marina di Pisa', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.MRN.D.PZ.');

INSERT INTO `!prefix_!location` VALUES (85, 'Getty TGN', '1045278', '7003163', 'Marradi', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MRD.');

INSERT INTO `!prefix_!location` VALUES (86, 'Getty TGN', '1045307', '7003165', 'Massarosa', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.MSRZ.');

INSERT INTO `!prefix_!location` VALUES (87, 'Getty TGN', '1045466', '7003167', 'Montale', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.MNTL.');

INSERT INTO `!prefix_!location` VALUES (88, 'Getty TGN', '1045544', '1003460', 'Montepescali', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.MNTP.');

INSERT INTO `!prefix_!location` VALUES (89, 'Getty TGN', '1045551', '7003168', 'Monteroni d''Arbia', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.MNTR.DRB.');

INSERT INTO `!prefix_!location` VALUES (90, 'Getty TGN', '1045557', '1003460', 'Monterotondo', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.MNTR.');

INSERT INTO `!prefix_!location` VALUES (91, 'Getty TGN', '1045563', '7003166', 'Montescudaio', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.MNTS.');

INSERT INTO `!prefix_!location` VALUES (92, 'Getty TGN', '1045566', '7003163', 'Montespertoli', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MNTS.');

INSERT INTO `!prefix_!location` VALUES (93, 'Getty TGN', '1045576', '7003168', 'Monticiano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.MNTC.');

INSERT INTO `!prefix_!location` VALUES (94, 'Getty TGN', '1045577', '1003460', 'Montieri', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.MNTR.');

INSERT INTO `!prefix_!location` VALUES (95, 'Getty TGN', '1045582', '7003166', 'Montopoli in Val d''Arno', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.MNTP.IN.VL.DRN.');

INSERT INTO `!prefix_!location` VALUES (96, 'Getty TGN', '1045612', '7003165', 'Motrone', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.MTRN.');

INSERT INTO `!prefix_!location` VALUES (97, 'Getty TGN', '1045629', '1003473', 'Mulazzo', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.MLZ.');

INSERT INTO `!prefix_!location` VALUES (98, 'Getty TGN', '1045754', '7003167', 'Oppio', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.OP.');

INSERT INTO `!prefix_!location` VALUES (99, 'Getty TGN', '1045918', '7003162', 'Pergine Valdarno', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.PRGN.VLDR.');

INSERT INTO `!prefix_!location` VALUES (100, 'Getty TGN', '1045931', '7003165', 'Pescaglia', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.PSKG.');

INSERT INTO `!prefix_!location` VALUES (101, 'Getty TGN', '1045959', '7003162', 'Pian di Sco', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.PN.D.SK.');

INSERT INTO `!prefix_!location` VALUES (102, 'Getty TGN', '1045962', '7003168', 'Piancastagnaio', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.PNKS.');

INSERT INTO `!prefix_!location` VALUES (103, 'Getty TGN', '1045970', '7003167', 'Pianosinatico', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.PNZN.');

INSERT INTO `!prefix_!location` VALUES (104, 'Getty TGN', '1045991', '7003163', 'Pietramala', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PTRM.');

INSERT INTO `!prefix_!location` VALUES (105, 'Getty TGN', '1046000', '7003165', 'Pieve Fosciana', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.PV.FSCN.');

INSERT INTO `!prefix_!location` VALUES (106, 'Getty TGN', '1046002', '7003162', 'Pieve Santo Stefano', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.PV.SNT.STFN.');

INSERT INTO `!prefix_!location` VALUES (107, 'Getty TGN', '1046031', '7003167', 'Piteglio', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.PTGL.');

INSERT INTO `!prefix_!location` VALUES (108, 'Getty TGN', '1046075', '7003166', 'Pomarance', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.PMRN.');

INSERT INTO `!prefix_!location` VALUES (109, 'Getty TGN', '1046082', '7003166', 'Ponsacco', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.PNSK.');

INSERT INTO `!prefix_!location` VALUES (110, 'Getty TGN', '1046089', '7003163', 'Ponte Ghiereto', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PNT.GRT.');

INSERT INTO `!prefix_!location` VALUES (111, 'Getty TGN', '1046095', '7003163', 'Ponte a Elsa', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PNT.A.ELS.');

INSERT INTO `!prefix_!location` VALUES (112, 'Getty TGN', '1046096', '7003165', 'Ponte a Moriano', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.PNT.A.MRN.');

INSERT INTO `!prefix_!location` VALUES (113, 'Getty TGN', '1046097', '7003168', 'Ponte d''Arbia', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.PNT.DRB.');

INSERT INTO `!prefix_!location` VALUES (114, 'Getty TGN', '1046118', '7003167', 'Pontepetri', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.PNTP.');

INSERT INTO `!prefix_!location` VALUES (115, 'Getty TGN', '1046135', '1003460', 'Port''Ercole', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.PRTR.');

INSERT INTO `!prefix_!location` VALUES (116, 'Getty TGN', '1046139', '7006197', 'Porto Azzurro', '83002/inhabited place', 'Elba, Isola d'' | Livorno province | Toscana | Italia | Europe', '.PRT.AZR.');

INSERT INTO `!prefix_!location` VALUES (117, 'Getty TGN', '1046148', '1003460', 'Porto Santo Stefano', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.PRT.SNT.STFN.');

INSERT INTO `!prefix_!location` VALUES (118, 'Getty TGN', '1046205', '7006197', 'Procchio', '83002/inhabited place', 'Elba, Isola d'' | Livorno province | Toscana | Italia | Europe', '.PRK.');

INSERT INTO `!prefix_!location` VALUES (119, 'Getty TGN', '1046210', '7003162', 'Puliciano', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.PLCN.');

INSERT INTO `!prefix_!location` VALUES (120, 'Getty TGN', '1046215', '7003167', 'Quarrata', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.KRT.');

INSERT INTO `!prefix_!location` VALUES (121, 'Getty TGN', '1046217', '7003164', 'Quercianella', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.KRCN.');

INSERT INTO `!prefix_!location` VALUES (122, 'Getty TGN', '1046228', '7003168', 'Radicofani', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.RDKF.');

INSERT INTO `!prefix_!location` VALUES (123, 'Getty TGN', '1046288', '7006197', 'Rio Marina', '83002/inhabited place', 'Elba, Isola d'' | Livorno province | Toscana | Italia | Europe', '.R.MRN.');

INSERT INTO `!prefix_!location` VALUES (124, 'Getty TGN', '1046302', '1003460', 'Riva del Sole', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.RV.DL.SL.');

INSERT INTO `!prefix_!location` VALUES (125, 'Getty TGN', '1046346', '1003460', 'Roccastrada', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.RKST.');

INSERT INTO `!prefix_!location` VALUES (126, 'Getty TGN', '1046384', '7003164', 'Rosignano Marittimo', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.RZGN.MRTM.');

INSERT INTO `!prefix_!location` VALUES (127, 'Getty TGN', '1046385', '7003164', 'Rosignano Solvay', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.RZGN.SLVY.');

INSERT INTO `!prefix_!location` VALUES (128, 'Getty TGN', '1046438', '7003166', 'Saline di Volterra', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.SLN.D.VLTR.');

INSERT INTO `!prefix_!location` VALUES (129, 'Getty TGN', '1046451', '7003167', 'Sambuca Pistoiese', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.SMBK.PSTZ.');

INSERT INTO `!prefix_!location` VALUES (130, 'Getty TGN', '1046545', '7003166', 'San Giuliano Terme', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.SN.GLN.TRM.');

INSERT INTO `!prefix_!location` VALUES (131, 'Getty TGN', '1046567', '7003167', 'San Marcello Pistoiese', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.SN.MRCL.PSTZ.');

INSERT INTO `!prefix_!location` VALUES (132, 'Getty TGN', '1046615', '7003164', 'San Pietro in Palazzi', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.SN.PTR.IN.PLZ.');

INSERT INTO `!prefix_!location` VALUES (133, 'Getty TGN', '1046641', '7003164', 'San Vincenzo', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.SN.VNCN.');

INSERT INTO `!prefix_!location` VALUES (134, 'Getty TGN', '1046703', '7003166', 'Santa Croce Sull''Arno', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.SNT.CRC.SLRN.');

INSERT INTO `!prefix_!location` VALUES (135, 'Getty TGN', '1046709', '1003460', 'Santa Fiora', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.SNT.FR.');

INSERT INTO `!prefix_!location` VALUES (136, 'Getty TGN', '1046712', '7003166', 'Santa Luce', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.SNT.LC.');

INSERT INTO `!prefix_!location` VALUES (137, 'Getty TGN', '1046721', '7003166', 'Santa Maria a Monte', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.SNT.MR.A.MNT.');

INSERT INTO `!prefix_!location` VALUES (138, 'Getty TGN', '1046802', '1003460', 'Scansano', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.SKNS.');

INSERT INTO `!prefix_!location` VALUES (139, 'Getty TGN', '1046803', '1003460', 'Scarlino', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.SKRL.');

INSERT INTO `!prefix_!location` VALUES (140, 'Getty TGN', '1047001', '7003165', 'Stazzema', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.STZM.');

INSERT INTO `!prefix_!location` VALUES (141, 'Getty TGN', '1047023', '7003162', 'Subbiano', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.SBN.');

INSERT INTO `!prefix_!location` VALUES (142, 'Getty TGN', '1047036', '7003164', 'Suvereto', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.SVRT.');

INSERT INTO `!prefix_!location` VALUES (143, 'Getty TGN', '1047087', '7003162', 'Terontola', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.TRNT.');

INSERT INTO `!prefix_!location` VALUES (144, 'Getty TGN', '1047093', '7003162', 'Terranuova Bracciolini', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.TRNV.BRKL.');

INSERT INTO `!prefix_!location` VALUES (145, 'Getty TGN', '1047112', '7003166', 'Tirrenia', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.TRN.');

INSERT INTO `!prefix_!location` VALUES (146, 'Getty TGN', '1047144', '7003165', 'Torre del Lago Puccini', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.TR.DL.LK.PKN.');

INSERT INTO `!prefix_!location` VALUES (147, 'Getty TGN', '1047151', '7003168', 'Torrenieri', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.TRNR.');

INSERT INTO `!prefix_!location` VALUES (148, 'Getty TGN', '1047166', '7003163', 'Tosi', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.TZ.');

INSERT INTO `!prefix_!location` VALUES (149, 'Getty TGN', '1047269', '7024741', 'Vaiano', '83002/inhabited place', 'Prato | Toscana | Italia | Europe', '.VN.');

INSERT INTO `!prefix_!location` VALUES (150, 'Getty TGN', '1047332', '7003166', 'Vecchiano', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.VKN.');

INSERT INTO `!prefix_!location` VALUES (151, 'Getty TGN', '1047338', '7003167', 'Vellano', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.VLN.');

INSERT INTO `!prefix_!location` VALUES (152, 'Getty TGN', '1047352', '7003164', 'Venturina', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.VNTR.');

INSERT INTO `!prefix_!location` VALUES (153, 'Getty TGN', '1047366', '7024741', 'Vernio', '83002/inhabited place', 'Prato | Toscana | Italia | Europe', '.VRN.');

INSERT INTO `!prefix_!location` VALUES (154, 'Getty TGN', '1047446', '1003473', 'Villafranca in Lunigiana', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.VLFR.IN.LNGN.');

INSERT INTO `!prefix_!location` VALUES (155, 'Getty TGN', '1047514', '1003473', 'Zeri', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.ZR.');

INSERT INTO `!prefix_!location` VALUES (156, 'Getty TGN', '1100052', '7003168', 'Monte Oliveto Maggiore, Abbazia del', '54414/abbey', 'Siena province | Toscana | Italia | Europe', '.MNT.OLVT.MGR.ABZ.DL.');

INSERT INTO `!prefix_!location` VALUES (157, 'Getty TGN', '1100124', '7003162', 'La Verna', '54413/monastery', 'Arezzo province | Toscana | Italia | Europe', '.L.VRN.');

INSERT INTO `!prefix_!location` VALUES (158, 'Getty TGN', '1100133', '7003166', 'Pisa, Certosa di', '54413/monastery', 'Pisa province | Toscana | Italia | Europe', '.PZ.CRTZ.D.');

INSERT INTO `!prefix_!location` VALUES (159, 'Getty TGN', '1101282', '7009760', 'Allochio, Galleria degli', '51845/tunnel', 'Toscana | Italia | Europe', '.ALCH.KLR.DGL.');

INSERT INTO `!prefix_!location` VALUES (160, 'Getty TGN', '1101289', '1003473', 'Borgallo, Galleria del', '51845/tunnel', 'Massa-Carrara province | Toscana | Italia | Europe', '.BRKL.KLR.DL.');

INSERT INTO `!prefix_!location` VALUES (161, 'Getty TGN', '1103685', '7009760', 'Albano, Monte', '21434/mountains', 'Toscana | Italia | Europe', '.ALBN.MNT.');

INSERT INTO `!prefix_!location` VALUES (162, 'Getty TGN', '1104120', '7003164', 'Calvi, Monte', '21430/mountain', 'Livorno province | Toscana | Italia | Europe', '.KLV.MNT.');

INSERT INTO `!prefix_!location` VALUES (163, 'Getty TGN', '1104138', '7006197', 'Capanne, Monte', '21430/mountain', 'Elba, Isola d'' | Livorno province | Toscana | Italia | Europe', '.KPN.MNT.');

INSERT INTO `!prefix_!location` VALUES (164, 'Getty TGN', '1104163', '1006715', 'Castello, Monte', '21430/mountain', 'Capraia, Isola di | Livorno province | Toscana | Italia | Europe', '.KSTL.MNT.');

INSERT INTO `!prefix_!location` VALUES (165, 'Getty TGN', '1104192', '7003168', 'Cetona, Monte', '21430/mountain', 'Siena province | Toscana | Italia | Europe', '.CTN.MNT.');

INSERT INTO `!prefix_!location` VALUES (166, 'Getty TGN', '1104629', '7003162', 'Frati, Monte dei', '21430/mountain', 'Arezzo province | Toscana | Italia | Europe', '.FRT.MNT.D.');

INSERT INTO `!prefix_!location` VALUES (167, 'Getty TGN', '1105021', '1003460', 'Telegrafo, Monte', '21430/mountain', 'Grosseto province | Toscana | Italia | Europe', '.TLGR.MNT.');

INSERT INTO `!prefix_!location` VALUES (168, 'Getty TGN', '1105368', '1003460', 'Le Cornate', '21430/mountain', 'Grosseto province | Toscana | Italia | Europe', '.L.KRNT.');

INSERT INTO `!prefix_!location` VALUES (169, 'Getty TGN', '1105685', '1003460', 'Montieri, Poggio di', '21430/mountain', 'Grosseto province | Toscana | Italia | Europe', '.MNTR.PG.D.');

INSERT INTO `!prefix_!location` VALUES (170, 'Getty TGN', '1106401', '7009760', 'Serra, Monte', '21430/mountain', 'Toscana | Italia | Europe', '.SR.MNT.');

INSERT INTO `!prefix_!location` VALUES (171, 'Getty TGN', '1106911', '7009760', 'Vigese, Monte', '21430/mountain', 'Toscana | Italia | Europe', '.VGZ.MNT.');

INSERT INTO `!prefix_!location` VALUES (172, 'Getty TGN', '1108367', '7003167', 'Collina, Passo di', '21433/pass', 'Pistoia province | Toscana | Italia | Europe', '.KLN.PS.D.');

INSERT INTO `!prefix_!location` VALUES (173, 'Getty TGN', '1108368', '7009760', 'Consuma, Passo della', '21433/pass', 'Toscana | Italia | Europe', '.KNSM.PS.DL.');

INSERT INTO `!prefix_!location` VALUES (174, 'Getty TGN', '1108469', '7009760', 'Mandrioli, Passo dei', '21433/pass', 'Toscana | Italia | Europe', '.MNDR.PS.D.');

INSERT INTO `!prefix_!location` VALUES (175, 'Getty TGN', '1108487', '7009760', 'Muraglione, Passo del', '21433/pass', 'Toscana | Italia | Europe', '.MRGL.PS.DL.');

INSERT INTO `!prefix_!location` VALUES (176, 'Getty TGN', '1108527', '7009760', 'Raticosa, Passo della', '21433/pass', 'Toscana | Italia | Europe', '.RTKZ.PS.DL.');

INSERT INTO `!prefix_!location` VALUES (177, 'Getty TGN', '1108590', '7003165', 'Terrarossa, Foce di', '21433/pass', 'Lucca province | Toscana | Italia | Europe', '.TRRS.FC.D.');

INSERT INTO `!prefix_!location` VALUES (178, 'Getty TGN', '1108724', '7009760', 'Apuane, Alpi', '21434/mountains', 'Toscana | Italia | Europe', '.APN.ALP.');

INSERT INTO `!prefix_!location` VALUES (179, 'Getty TGN', '1108893', '7009760', 'Chianti, Monti del', '21431/mountain range', 'Toscana | Italia | Europe', '.CHNT.MNT.DL.');

INSERT INTO `!prefix_!location` VALUES (180, 'Getty TGN', '1109344', '7009760', 'Metallifere, Colline', '21434/mountains', 'Toscana | Italia | Europe', '.MTLF.KLN.');

INSERT INTO `!prefix_!location` VALUES (181, 'Getty TGN', '1109363', '7003168', 'Montagnola', '21434/mountains', 'Siena province | Toscana | Italia | Europe', '.MNTG.');

INSERT INTO `!prefix_!location` VALUES (182, 'Getty TGN', '1109495', '7009760', 'Pratomagno', '21491/ridge', 'Toscana | Italia | Europe', '.PRTM.');

INSERT INTO `!prefix_!location` VALUES (183, 'Getty TGN', '1109756', '1003460', 'Ucellina, Monti dell''', '21431/mountain range', 'Grosseto province | Toscana | Italia | Europe', '.UCLN.MNT.DL.');

INSERT INTO `!prefix_!location` VALUES (184, 'Getty TGN', '1111810', '7009760', 'Valdarno', '22101/general region', 'Toscana | Italia | Europe', '.VLDR.');

INSERT INTO `!prefix_!location` VALUES (185, 'Getty TGN', '1112063', '7009760', 'Lunigiana', '22101/general region', 'Toscana | Italia | Europe', '.LNGN.');

INSERT INTO `!prefix_!location` VALUES (186, 'Getty TGN', '1112377', '1003460', 'Follonica, Golfo di', '21123/gulf', 'Grosseto province | Toscana | Italia | Europe', '.FLNK.KLF.D.');

INSERT INTO `!prefix_!location` VALUES (187, 'Getty TGN', '1114232', '7003164', 'Piombino, Canale di', '21151/channel', 'Livorno province | Toscana | Italia | Europe', '.PMBN.KNL.D.');

INSERT INTO `!prefix_!location` VALUES (188, 'Getty TGN', '1114802', '1003460', 'Orbetello, Laguna di', '21125/lagoon', 'Grosseto province | Toscana | Italia | Europe', '.ORBT.LN.D.');

INSERT INTO `!prefix_!location` VALUES (189, 'Getty TGN', '1115906', '7003168', 'Chiusi, Lago di', '21115/lake', 'Siena province | Toscana | Italia | Europe', '.CHZ.LK.D.');

INSERT INTO `!prefix_!location` VALUES (190, 'Getty TGN', '1117234', '7003165', 'Massaciuccoli, Lago di', '21115/lake', 'Lucca province | Toscana | Italia | Europe', '.MSCK.LK.D.');

INSERT INTO `!prefix_!location` VALUES (191, 'Getty TGN', '1121328', '7009760', 'Arbia', '21105/river', 'Toscana | Italia | Europe', '.ARB.');

INSERT INTO `!prefix_!location` VALUES (192, 'Getty TGN', '1121388', '7009760', 'Arno', '21105/river', 'Toscana | Italia | Europe', '.ARN.');

INSERT INTO `!prefix_!location` VALUES (193, 'Getty TGN', '1121940', '7009760', 'Bisenzio', '21105/river', 'Toscana | Italia | Europe', '.BZNZ.');

INSERT INTO `!prefix_!location` VALUES (194, 'Getty TGN', '1122250', '1003460', 'Bruna', '21105/river', 'Grosseto province | Toscana | Italia | Europe', '.BRN.');

INSERT INTO `!prefix_!location` VALUES (195, 'Getty TGN', '1122612', '7009760', 'Cecina', '21105/river', 'Toscana | Italia | Europe', '.CN.');

INSERT INTO `!prefix_!location` VALUES (196, 'Getty TGN', '1123066', '7009760', 'Cornia', '21105/river', 'Toscana | Italia | Europe', '.KRN.');

INSERT INTO `!prefix_!location` VALUES (197, 'Getty TGN', '1123775', '7009760', 'Era', '21105/river', 'Toscana | Italia | Europe', '.ER.');

INSERT INTO `!prefix_!location` VALUES (198, 'Getty TGN', '1123823', '7009760', 'Esse', '21105/river', 'Toscana | Italia | Europe', '.ES.');

INSERT INTO `!prefix_!location` VALUES (199, 'Getty TGN', '1124371', '7009760', 'Greve', '21105/river', 'Toscana | Italia | Europe', '.GRV.');

INSERT INTO `!prefix_!location` VALUES (200, 'Getty TGN', '1126117', '7009760', 'Lima', '21105/river', 'Toscana | Italia | Europe', '.LM.');

INSERT INTO `!prefix_!location` VALUES (201, 'Getty TGN', '1126122', '7009760', 'Limentra', '21105/river', 'Toscana | Italia | Europe', '.LMNT.');

INSERT INTO `!prefix_!location` VALUES (202, 'Getty TGN', '1126668', '1003473', 'Magra', '21105/river', 'Massa-Carrara province | Toscana | Italia | Europe', '.MGR.');

INSERT INTO `!prefix_!location` VALUES (203, 'Getty TGN', '1127088', '7009760', 'Merse', '21105/river', 'Toscana | Italia | Europe', '.MRS.');

INSERT INTO `!prefix_!location` VALUES (204, 'Getty TGN', '1128089', '7009760', 'Ombrone', '21105/river', 'Toscana | Italia | Europe', '.OMBR.');

INSERT INTO `!prefix_!location` VALUES (205, 'Getty TGN', '1128141', '7009760', 'Orcia', '21105/river', 'Toscana | Italia | Europe', '.ORC.');

INSERT INTO `!prefix_!location` VALUES (206, 'Getty TGN', '1128558', '7009760', 'Pesa', '21105/river', 'Toscana | Italia | Europe', '.PZ.');

INSERT INTO `!prefix_!location` VALUES (207, 'Getty TGN', '1129867', '7009760', 'Serchio', '21105/river', 'Toscana | Italia | Europe', '.SRCH.');

INSERT INTO `!prefix_!location` VALUES (208, 'Getty TGN', '1130008', '7003163', 'Sieve', '21105/river', 'Firenze province | Toscana | Italia | Europe', '.SV.');

INSERT INTO `!prefix_!location` VALUES (209, 'Getty TGN', '1137022', '1003473', 'Tavernelle', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.TVRN.');

INSERT INTO `!prefix_!location` VALUES (210, 'Getty TGN', '4000491', '1003460', 'Alberese', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.ALBR.');

INSERT INTO `!prefix_!location` VALUES (211, 'Getty TGN', '4000862', '7003163', 'Artimino', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.ARTM.');

INSERT INTO `!prefix_!location` VALUES (212, 'Getty TGN', '4001112', '7000457', 'Badia a Ripoli', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.BD.A.RPL.');

INSERT INTO `!prefix_!location` VALUES (213, 'Getty TGN', '4001206', '7003164', 'Baratti', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.BRT.');

INSERT INTO `!prefix_!location` VALUES (214, 'Getty TGN', '4001386', '7003165', 'Benabbio', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.BNB.');

INSERT INTO `!prefix_!location` VALUES (215, 'Getty TGN', '4001692', '7003167', 'Borgo a Buggiano', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.BRK.A.BGN.');

INSERT INTO `!prefix_!location` VALUES (216, 'Getty TGN', '4001976', '7003167', 'Buggiano Castello', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.BGN.KSTL.');

INSERT INTO `!prefix_!location` VALUES (217, 'Getty TGN', '4002142', '7003165', 'Camigliano Santa Gemma', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.KMGL.SNT.GM.');

INSERT INTO `!prefix_!location` VALUES (218, 'Getty TGN', '4002143', '7003163', 'Camoggiano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KMGN.');

INSERT INTO `!prefix_!location` VALUES (219, 'Getty TGN', '4002281', '7003163', 'Carteano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KRTN.');

INSERT INTO `!prefix_!location` VALUES (220, 'Getty TGN', '4002300', '7003163', 'Cascia', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KSC.');

INSERT INTO `!prefix_!location` VALUES (221, 'Getty TGN', '4002302', '7003166', 'Casciana Alta', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.KSCN.ALT.');

INSERT INTO `!prefix_!location` VALUES (222, 'Getty TGN', '4002333', '7003163', 'Castelbonsi', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KSTL.');

INSERT INTO `!prefix_!location` VALUES (223, 'Getty TGN', '4002344', '1003460', 'Castelletto Accarigi', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.KSTL.AKRG.');

INSERT INTO `!prefix_!location` VALUES (224, 'Getty TGN', '4002353', '7003163', 'Castelnuovo d''Elsa', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KSTL.DLS.');

INSERT INTO `!prefix_!location` VALUES (225, 'Getty TGN', '4002427', '7003168', 'Cellole', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.CLL.');

INSERT INTO `!prefix_!location` VALUES (226, 'Getty TGN', '4002439', '7003163', 'Cerbaia', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.CRB.');

INSERT INTO `!prefix_!location` VALUES (227, 'Getty TGN', '4002640', '7003168', 'Chiusdino', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.CHSD.');

INSERT INTO `!prefix_!location` VALUES (228, 'Getty TGN', '4002660', '7003162', 'Cicogna', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.CKGN.');

INSERT INTO `!prefix_!location` VALUES (229, 'Getty TGN', '4002753', '1003473', 'Codiponte', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.KDPN.');

INSERT INTO `!prefix_!location` VALUES (230, 'Getty TGN', '4002779', '7003163', 'Collebarucci', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KLBR.');

INSERT INTO `!prefix_!location` VALUES (231, 'Getty TGN', '4002789', '7003167', 'Collina', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.KLN.');

INSERT INTO `!prefix_!location` VALUES (232, 'Getty TGN', '4002793', '7003167', 'Collodi', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.KLD.');

INSERT INTO `!prefix_!location` VALUES (233, 'Getty TGN', '4002803', '7003164', 'Colognole', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.KLGN.');

INSERT INTO `!prefix_!location` VALUES (234, 'Getty TGN', '4002880', '7003163', 'Corbignano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KRBG.');

INSERT INTO `!prefix_!location` VALUES (235, 'Getty TGN', '4003081', '7003168', 'Cuna', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KN.');

INSERT INTO `!prefix_!location` VALUES (236, 'Getty TGN', '4003317', '7003163', 'Doccia', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.DK.');

INSERT INTO `!prefix_!location` VALUES (237, 'Getty TGN', '4003884', '7003163', 'Ferrano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.FRN.');

INSERT INTO `!prefix_!location` VALUES (238, 'Getty TGN', '4003903', '7003163', 'Filettole', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.FLTL.');

INSERT INTO `!prefix_!location` VALUES (239, 'Getty TGN', '4003918', '7009760', 'Republic of Florence', '81508/former nation/state/empire', 'Toscana | Italia | Europe', '.RPBL.OF.FLRN.');

INSERT INTO `!prefix_!location` VALUES (240, 'Getty TGN', '4003976', '7003163', 'Fonte Lucente', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.FNT.LCNT.');

INSERT INTO `!prefix_!location` VALUES (241, 'Getty TGN', '4004239', '7003162', 'Gargonza', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.KRKN.');

INSERT INTO `!prefix_!location` VALUES (242, 'Getty TGN', '4004361', '1003460', 'Giuncarico', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.GNKR.');

INSERT INTO `!prefix_!location` VALUES (243, 'Getty TGN', '4004422', '7003163', 'Gonfienti', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KNFN.');

INSERT INTO `!prefix_!location` VALUES (244, 'Getty TGN', '4004519', '7003163', 'Grassina', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.GRSN.');

INSERT INTO `!prefix_!location` VALUES (245, 'Getty TGN', '4005345', '7003164', 'Isola di Gorgona', '21471/island', 'Livorno province | Toscana | Italia | Europe', '.ISL.D.KRKN.');

INSERT INTO `!prefix_!location` VALUES (246, 'Getty TGN', '4006085', '7003165', 'Lammari', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.LMR.');

INSERT INTO `!prefix_!location` VALUES (247, 'Getty TGN', '4006399', '7003163', 'Limite sull''Arno', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.LMT.SLRN.');

INSERT INTO `!prefix_!location` VALUES (248, 'Getty TGN', '4006636', '7003163', 'Luco di Mugello', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.LK.D.MGL.');

INSERT INTO `!prefix_!location` VALUES (249, 'Getty TGN', '4006667', '1003473', 'Lusuolo', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.LZL.');

INSERT INTO `!prefix_!location` VALUES (250, 'Getty TGN', '4006836', '7003163', 'Malmantile', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MLMN.');

INSERT INTO `!prefix_!location` VALUES (251, 'Getty TGN', '4006949', '7003165', 'Marlia', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.MRL.');

INSERT INTO `!prefix_!location` VALUES (252, 'Getty TGN', '4006994', '7003165', 'Massa Pisana', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.MS.PZN.');

INSERT INTO `!prefix_!location` VALUES (253, 'Getty TGN', '4007147', '7003162', 'Metelliano', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.MTLN.');

INSERT INTO `!prefix_!location` VALUES (254, 'Getty TGN', '4007376', '7003168', 'Montarrenti', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.MNTR.');

INSERT INTO `!prefix_!location` VALUES (255, 'Getty TGN', '4007395', '7003165', 'Monte San Quirico', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.MNT.SN.KRK.');

INSERT INTO `!prefix_!location` VALUES (256, 'Getty TGN', '4007415', '7003162', 'Montedoglio', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.MNTD.');

INSERT INTO `!prefix_!location` VALUES (257, 'Getty TGN', '4007418', '7003163', 'Montefi$02esole', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MNTF.');

INSERT INTO `!prefix_!location` VALUES (258, 'Getty TGN', '4007428', '7003165', 'Montemagno', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.MNTM.');

INSERT INTO `!prefix_!location` VALUES (259, 'Getty TGN', '4007430', '1003460', 'Montemassi', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.MNTM.');

INSERT INTO `!prefix_!location` VALUES (260, 'Getty TGN', '4007432', '7024741', 'Montemurlo', '83002/inhabited place', 'Prato | Toscana | Italia | Europe', '.MNTM.');

INSERT INTO `!prefix_!location` VALUES (261, 'Getty TGN', '4007436', '7003163', 'Monterappoli', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MNTR.');

INSERT INTO `!prefix_!location` VALUES (262, 'Getty TGN', '4007536', '7003163', 'Morniano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MRN.');

INSERT INTO `!prefix_!location` VALUES (263, 'Getty TGN', '4007544', '7003164', 'Mortaiolo', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.MRTL.');

INSERT INTO `!prefix_!location` VALUES (264, 'Getty TGN', '4007713', '7003163', 'Nave a Rovezzano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.NV.A.RVZN.');

INSERT INTO `!prefix_!location` VALUES (265, 'Getty TGN', '4008606', '7003168', 'Petroio', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.PTR.');

INSERT INTO `!prefix_!location` VALUES (266, 'Getty TGN', '4008629', '7003163', 'Pian di Mugnone', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PN.D.MGN.');

INSERT INTO `!prefix_!location` VALUES (267, 'Getty TGN', '4008630', '7003163', 'Pianezzoli', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PNZL.');

INSERT INTO `!prefix_!location` VALUES (268, 'Getty TGN', '4008633', '7003167', 'Piazza', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.PZ.');

INSERT INTO `!prefix_!location` VALUES (269, 'Getty TGN', '4008700', '7003163', 'Pizzidimonte', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PZDM.');

INSERT INTO `!prefix_!location` VALUES (270, 'Getty TGN', '4008796', '7000457', 'Ponte a Ema', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.PNT.A.EM.');

INSERT INTO `!prefix_!location` VALUES (271, 'Getty TGN', '4008821', '7003167', 'Popiglio', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.PGL.');

INSERT INTO `!prefix_!location` VALUES (272, 'Getty TGN', '4008823', '7003163', 'Poppiano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PPN.');

INSERT INTO `!prefix_!location` VALUES (273, 'Getty TGN', '4008833', '7003162', 'Porrena', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.PRN.');

INSERT INTO `!prefix_!location` VALUES (274, 'Getty TGN', '4009026', '7003168', 'Quercegrossa', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KRCG.');

INSERT INTO `!prefix_!location` VALUES (275, 'Getty TGN', '4009191', '7003163', 'Remole', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.RML.');

INSERT INTO `!prefix_!location` VALUES (276, 'Getty TGN', '4009196', '7003168', 'Rencine', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.RNCN.');

INSERT INTO `!prefix_!location` VALUES (277, 'Getty TGN', '4009272', '7000457', 'Rifredi', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.RFRD.');

INSERT INTO `!prefix_!location` VALUES (278, 'Getty TGN', '4009376', '7003165', 'Roggio', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.RG.');

INSERT INTO `!prefix_!location` VALUES (279, 'Getty TGN', '4009496', '7003163', 'Ruballa', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.RBL.');

INSERT INTO `!prefix_!location` VALUES (280, 'Getty TGN', '4009900', '7003163', 'San Donato in Poggio', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.DNT.IN.PG.');

INSERT INTO `!prefix_!location` VALUES (281, 'Getty TGN', '4009905', '7003168', 'San Filippo', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SN.FLP.');

INSERT INTO `!prefix_!location` VALUES (282, 'Getty TGN', '4009908', '7003165', 'San Gennaro', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.SN.GNR.');

INSERT INTO `!prefix_!location` VALUES (283, 'Getty TGN', '4009921', '7003168', 'San Gusm$02e', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SN.KSM.');

INSERT INTO `!prefix_!location` VALUES (284, 'Getty TGN', '4009934', '7003163', 'San Leonardo in Collina', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.LNRD.IN.KLN.');

INSERT INTO `!prefix_!location` VALUES (285, 'Getty TGN', '4009964', '7003163', 'San Polo in Chianti', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.PL.IN.CHNT.');

INSERT INTO `!prefix_!location` VALUES (286, 'Getty TGN', '4009967', '7003163', 'San Quirico Vernio', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.KRK.VRN.');

INSERT INTO `!prefix_!location` VALUES (287, 'Getty TGN', '4009970', '7003166', 'San Romano', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.SN.RMN.');

INSERT INTO `!prefix_!location` VALUES (288, 'Getty TGN', '4009978', '7003168', 'San Vincenzio', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SN.VNCN.');

INSERT INTO `!prefix_!location` VALUES (289, 'Getty TGN', '4009984', '7003163', 'San Vivaldo', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.VLD.');

INSERT INTO `!prefix_!location` VALUES (290, 'Getty TGN', '4010042', '7003163', 'Sant''Appiano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SNTP.');

INSERT INTO `!prefix_!location` VALUES (291, 'Getty TGN', '4010045', '7003163', 'Sant''Ellero', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SNTL.');

INSERT INTO `!prefix_!location` VALUES (292, 'Getty TGN', '4010067', '7003168', 'Santa Margherita a Casciano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SNT.MRGR.A.KSCN.');

INSERT INTO `!prefix_!location` VALUES (293, 'Getty TGN', '4010288', '7003165', 'Segromigno Monte', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.SGRM.MNT.');

INSERT INTO `!prefix_!location` VALUES (294, 'Getty TGN', '4010336', '7003163', 'Serpiolle', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SRPL.');

INSERT INTO `!prefix_!location` VALUES (295, 'Getty TGN', '4010338', '7003167', 'Serra Pistoiese', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.SR.PSTZ.');

INSERT INTO `!prefix_!location` VALUES (296, 'Getty TGN', '4010360', '7003163', 'Settimello', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.STML.');

INSERT INTO `!prefix_!location` VALUES (297, 'Getty TGN', '4010671', '7003167', 'Spedaletto', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.SPDL.');

INSERT INTO `!prefix_!location` VALUES (298, 'Getty TGN', '4011334', '1003473', 'Traverde', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.TRVR.');

INSERT INTO `!prefix_!location` VALUES (299, 'Getty TGN', '4011352', '7003163', 'Trespiano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.TRSP.');

INSERT INTO `!prefix_!location` VALUES (300, 'Getty TGN', '4011602', '7003167', 'Valdibure', '22101/general region', 'Pistoia province | Toscana | Italia | Europe', '.VLDB.');

INSERT INTO `!prefix_!location` VALUES (301, 'Getty TGN', '4011603', '7009760', 'Valdinievole', '21451/valley', 'Toscana | Italia | Europe', '.VLDN.');

INSERT INTO `!prefix_!location` VALUES (302, 'Getty TGN', '4011735', '1003473', 'Verr$00ucola', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.VRKL.');

INSERT INTO `!prefix_!location` VALUES (303, 'Getty TGN', '4011776', '7003165', 'Vicopelago', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.VKPL.');

INSERT INTO `!prefix_!location` VALUES (304, 'Getty TGN', '4011913', '7003168', 'Vivo d''$00Orcia', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.V.DRC.');

INSERT INTO `!prefix_!location` VALUES (305, 'Getty TGN', '5002255', '7003163', 'Mezzano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MZN.');

INSERT INTO `!prefix_!location` VALUES (306, 'Getty TGN', '5002811', '7003163', 'Passignano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PSGN.');

INSERT INTO `!prefix_!location` VALUES (307, 'Getty TGN', '5002812', '7003168', 'Frosini', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.FRZN.');

INSERT INTO `!prefix_!location` VALUES (308, 'Getty TGN', '5002824', '7003168', 'Dolciano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.DLCN.');

INSERT INTO `!prefix_!location` VALUES (309, 'Getty TGN', '5002825', '7003163', 'Quinto Fiorentino', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KNT.FRNT.');

INSERT INTO `!prefix_!location` VALUES (310, 'Getty TGN', '5002835', '7003162', 'Castelfranco di Sopra', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.KSTL.D.SPR.');

INSERT INTO `!prefix_!location` VALUES (311, 'Getty TGN', '5002844', '7003167', 'Porciano', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.PRCN.');

INSERT INTO `!prefix_!location` VALUES (312, 'Getty TGN', '5002845', '7003162', 'Pratovecchio', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.PRTV.');

INSERT INTO `!prefix_!location` VALUES (313, 'Getty TGN', '5002848', '7003162', 'Serravalle', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.SRVL.');

INSERT INTO `!prefix_!location` VALUES (314, 'Getty TGN', '5002851', '7000457', 'Arcetri', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.ARCT.');

INSERT INTO `!prefix_!location` VALUES (315, 'Getty TGN', '5002856', '7000457', 'Brozzi', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.BRZ.');

INSERT INTO `!prefix_!location` VALUES (316, 'Getty TGN', '5002857', '7003167', 'Buggiano', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.BGN.');

INSERT INTO `!prefix_!location` VALUES (317, 'Getty TGN', '5002859', '7003163', 'Caldine', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KLDN.');

INSERT INTO `!prefix_!location` VALUES (318, 'Getty TGN', '5002866', '7003163', 'Castellonchio', '51224/castle', 'Firenze province | Toscana | Italia | Europe', '.KSTL.');

INSERT INTO `!prefix_!location` VALUES (319, 'Getty TGN', '5002871', '7003163', 'Cintoia', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.CNT.');

INSERT INTO `!prefix_!location` VALUES (320, 'Getty TGN', '5002872', '7003163', 'Compiobbi', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KMPB.');

INSERT INTO `!prefix_!location` VALUES (321, 'Getty TGN', '5002873', '7003163', 'Due Madonne', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.D.MDN.');

INSERT INTO `!prefix_!location` VALUES (322, 'Getty TGN', '5002876', '7003162', 'Faltona', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.FLTN.');

INSERT INTO `!prefix_!location` VALUES (323, 'Getty TGN', '5002884', '7009760', 'Il Girone', '21153/meander', 'Toscana | Italia | Europe', '.IL.GRN.');

INSERT INTO `!prefix_!location` VALUES (324, 'Getty TGN', '5002885', '7003163', 'Greti', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.GRT.');

INSERT INTO `!prefix_!location` VALUES (325, 'Getty TGN', '5002887', '7003162', 'Gr$00opina', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.GRPN.');

INSERT INTO `!prefix_!location` VALUES (326, 'Getty TGN', '5002890', '7003163', 'Linari', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.LNR.');

INSERT INTO `!prefix_!location` VALUES (327, 'Getty TGN', '5002891', '7003163', 'Marignolle', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MRGN.');

INSERT INTO `!prefix_!location` VALUES (328, 'Getty TGN', '5002892', '7003163', 'Mezzomonte', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MZMN.');

INSERT INTO `!prefix_!location` VALUES (329, 'Getty TGN', '5002893', '7003163', 'Mirans$02u', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MRNS.');

INSERT INTO `!prefix_!location` VALUES (330, 'Getty TGN', '5002894', '7003163', 'Montebuoni', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MNTB.');

INSERT INTO `!prefix_!location` VALUES (331, 'Getty TGN', '5002895', '7003166', 'Montefl$00oscoli', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.MNTF.');

INSERT INTO `!prefix_!location` VALUES (332, 'Getty TGN', '5002898', '7003162', 'Montemignaio', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.MNTM.');

INSERT INTO `!prefix_!location` VALUES (333, 'Getty TGN', '5002899', '7003163', 'Montepaldi', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MNTP.');

INSERT INTO `!prefix_!location` VALUES (334, 'Getty TGN', '5002901', '7003163', 'Morrocco', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MRK.');

INSERT INTO `!prefix_!location` VALUES (335, 'Getty TGN', '5002902', '7003163', 'Mosciano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MSCN.');

INSERT INTO `!prefix_!location` VALUES (336, 'Getty TGN', '5002903', '7003163', 'Mugnana', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MGN.');

INSERT INTO `!prefix_!location` VALUES (337, 'Getty TGN', '5002904', '7000457', 'N$00ovoli', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.NVL.');

INSERT INTO `!prefix_!location` VALUES (338, 'Getty TGN', '5002905', '7003163', 'Olena', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.OLN.');

INSERT INTO `!prefix_!location` VALUES (339, 'Getty TGN', '5002906', '7003163', 'Osteria Nuova', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.OSTR.NV.');

INSERT INTO `!prefix_!location` VALUES (340, 'Getty TGN', '5002909', '7000457', 'Per$00etola', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.PRTL.');

INSERT INTO `!prefix_!location` VALUES (341, 'Getty TGN', '5002912', '7003163', 'Pomino', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PMN.');

INSERT INTO `!prefix_!location` VALUES (342, 'Getty TGN', '5002914', '7003163', 'Ponte a M$00ensola', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PNT.A.MNSL.');

INSERT INTO `!prefix_!location` VALUES (343, 'Getty TGN', '5002915', '7003163', 'Pontorme', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PNTR.');

INSERT INTO `!prefix_!location` VALUES (344, 'Getty TGN', '5002918', '7003163', 'Qu$00intole', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KNTL.');

INSERT INTO `!prefix_!location` VALUES (345, 'Getty TGN', '5002919', '7003163', 'R$00iconi', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.RKN.');

INSERT INTO `!prefix_!location` VALUES (346, 'Getty TGN', '5002920', '7003163', 'Rosano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.RZN.');

INSERT INTO `!prefix_!location` VALUES (347, 'Getty TGN', '5002921', '7003163', 'Le Rose', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.L.RZ.');

INSERT INTO `!prefix_!location` VALUES (348, 'Getty TGN', '5002922', '7000457', 'Rovezzano', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.RVZN.');

INSERT INTO `!prefix_!location` VALUES (349, 'Getty TGN', '5002923', '7003163', 'Sant''Agata', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SNTK.');

INSERT INTO `!prefix_!location` VALUES (350, 'Getty TGN', '5002924', '7003163', 'Sagginale', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SGNL.');

INSERT INTO `!prefix_!location` VALUES (351, 'Getty TGN', '5002926', '7003163', 'San Domenico di Fi$02esole', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.DMNK.D.FZL.');

INSERT INTO `!prefix_!location` VALUES (352, 'Getty TGN', '5002927', '7003163', 'San Donato in Collina', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.DNT.IN.KLN.');

INSERT INTO `!prefix_!location` VALUES (353, 'Getty TGN', '5002928', '7003163', 'San Donnino', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.DNN.');

INSERT INTO `!prefix_!location` VALUES (354, 'Getty TGN', '5002929', '7000457', 'San Felice a Ema', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.SN.FLC.A.EM.');

INSERT INTO `!prefix_!location` VALUES (355, 'Getty TGN', '5002930', '7003163', 'San Giorgio a Ruballa', '51821/church', 'Firenze province | Toscana | Italia | Europe', '.SN.GRG.A.RBL.');

INSERT INTO `!prefix_!location` VALUES (356, 'Getty TGN', '5002931', '7003163', 'San Giovanni in Sugana', '51821/church', 'Firenze province | Toscana | Italia | Europe', '.SN.GVN.IN.SKN.');

INSERT INTO `!prefix_!location` VALUES (357, 'Getty TGN', '5002934', '7003163', 'San Pietro a L$00ecore', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.PTR.A.LKR.');

INSERT INTO `!prefix_!location` VALUES (358, 'Getty TGN', '5002935', '7003163', 'Vico l''Abate', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.VK.LBT.');

INSERT INTO `!prefix_!location` VALUES (359, 'Getty TGN', '5002936', '7003163', 'Sant''Ansano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SNTN.');

INSERT INTO `!prefix_!location` VALUES (360, 'Getty TGN', '5002937', '7003163', 'Santa Brigida', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SNT.BRGD.');

INSERT INTO `!prefix_!location` VALUES (361, 'Getty TGN', '5002941', '7003163', 'Le Sieci', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.L.SC.');

INSERT INTO `!prefix_!location` VALUES (362, 'Getty TGN', '5002943', '7003163', 'Signano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SGN.');

INSERT INTO `!prefix_!location` VALUES (363, 'Getty TGN', '5002945', '7003163', 'Terenzano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.TRNZ.');

INSERT INTO `!prefix_!location` VALUES (364, 'Getty TGN', '5002947', '7000457', 'Varlungo', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.VRLN.');

INSERT INTO `!prefix_!location` VALUES (365, 'Getty TGN', '5002948', '7003163', 'Verruca', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.VRK.');

INSERT INTO `!prefix_!location` VALUES (366, 'Getty TGN', '5002951', '7003163', 'Vicchio di Rimaggio', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.VK.D.RMG.');

INSERT INTO `!prefix_!location` VALUES (367, 'Getty TGN', '5002952', '7003166', 'Villamagna', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.VLMG.');

INSERT INTO `!prefix_!location` VALUES (368, 'Getty TGN', '5002962', '1003460', 'Montemerano', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.MNTM.');

INSERT INTO `!prefix_!location` VALUES (369, 'Getty TGN', '5002965', '1003460', 'Poggio di Moscona', '21437/hill', 'Grosseto province | Toscana | Italia | Europe', '.PG.D.MSKN.');

INSERT INTO `!prefix_!location` VALUES (370, 'Getty TGN', '5002967', '1003460', 'Poggioferro', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.PGFR.');

INSERT INTO `!prefix_!location` VALUES (371, 'Getty TGN', '5002971', '1003460', 'Semproniano', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.SMPR.');

INSERT INTO `!prefix_!location` VALUES (372, 'Getty TGN', '5002975', '1003460', 'Rocchette', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.RKT.');

INSERT INTO `!prefix_!location` VALUES (373, 'Getty TGN', '5002976', '7006197', 'Bagnaio', '83002/inhabited place', 'Elba, Isola d'' | Livorno province | Toscana | Italia | Europe', '.BGN.');

INSERT INTO `!prefix_!location` VALUES (374, 'Getty TGN', '5002982', '1003460', 'Pereta', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.PRT.');

INSERT INTO `!prefix_!location` VALUES (375, 'Getty TGN', '5002987', '7003165', 'Granaiola', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.GRNL.');

INSERT INTO `!prefix_!location` VALUES (376, 'Getty TGN', '5002989', '7003165', 'Nozzano', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.NZN.');

INSERT INTO `!prefix_!location` VALUES (377, 'Getty TGN', '5002990', '7003165', 'Petrognano', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.PTRG.');

INSERT INTO `!prefix_!location` VALUES (378, 'Getty TGN', '5002991', '7003165', 'Santa Maria del Giudice', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.SNT.MR.DL.GDC.');

INSERT INTO `!prefix_!location` VALUES (379, 'Getty TGN', '5002992', '7003165', 'Valdicastello Carducci', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.VLDK.KRDK.');

INSERT INTO `!prefix_!location` VALUES (380, 'Getty TGN', '5002994', '1003473', 'Avenza', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.AVNZ.');

INSERT INTO `!prefix_!location` VALUES (381, 'Getty TGN', '5002996', '1003473', 'Malgrate', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.MLGR.');

INSERT INTO `!prefix_!location` VALUES (382, 'Getty TGN', '5002997', '1003473', 'Torano', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.TRN.');

INSERT INTO `!prefix_!location` VALUES (383, 'Getty TGN', '5003004', '7003166', 'C$00evoli', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.CVL.');

INSERT INTO `!prefix_!location` VALUES (384, 'Getty TGN', '5003005', '7003166', 'Cr$00espina', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.CRSP.');

INSERT INTO `!prefix_!location` VALUES (385, 'Getty TGN', '5003009', '7003166', 'Ripoli', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.RPL.');

INSERT INTO `!prefix_!location` VALUES (386, 'Getty TGN', '5003011', '7003166', 'Soiana', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.SN.');

INSERT INTO `!prefix_!location` VALUES (387, 'Getty TGN', '5003014', '7003167', 'Montevettolini', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.MNTV.');

INSERT INTO `!prefix_!location` VALUES (388, 'Getty TGN', '5003017', '7003167', 'San Felice', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.SN.FLC.');

INSERT INTO `!prefix_!location` VALUES (389, 'Getty TGN', '5003019', '7003168', 'Argiano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.ARGN.');

INSERT INTO `!prefix_!location` VALUES (390, 'Getty TGN', '5003021', '7003168', 'Badia a Isola', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.BD.A.ISL.');

INSERT INTO `!prefix_!location` VALUES (391, 'Getty TGN', '5003024', '7003168', 'Castello di Belcaro', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KSTL.D.BLKR.');

INSERT INTO `!prefix_!location` VALUES (392, 'Getty TGN', '5003027', '7003168', 'Cedda', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.CD.');

INSERT INTO `!prefix_!location` VALUES (393, 'Getty TGN', '5003031', '7003168', 'Chiusure', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.CHZR.');

INSERT INTO `!prefix_!location` VALUES (394, 'Getty TGN', '5003033', '7003168', 'Costafabbri', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KSTF.');

INSERT INTO `!prefix_!location` VALUES (395, 'Getty TGN', '5003035', '7003168', 'Mensano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.MNSN.');

INSERT INTO `!prefix_!location` VALUES (396, 'Getty TGN', '5003038', '7003168', 'Montevenere', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.MNTV.');

INSERT INTO `!prefix_!location` VALUES (397, 'Getty TGN', '5003049', '7003168', 'San Leonardo al Lago', '54413/monastery', 'Siena province | Toscana | Italia | Europe', '.SN.LNRD.AL.LK.');

INSERT INTO `!prefix_!location` VALUES (398, 'Getty TGN', '5003051', '7003168', 'Sant''Anna in Camprena', '54413/monastery', 'Siena province | Toscana | Italia | Europe', '.SNTN.IN.KMPR.');

INSERT INTO `!prefix_!location` VALUES (399, 'Getty TGN', '5003060', '7003166', 'Montemiccioli', '52461/tower', 'Pisa province | Toscana | Italia | Europe', '.MNTM.');

INSERT INTO `!prefix_!location` VALUES (400, 'Getty TGN', '5004321', '1003460', 'Cerreto', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.CRT.');

INSERT INTO `!prefix_!location` VALUES (401, 'Getty TGN', '6005278', '1003460', 'Saturnia', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.STRN.');

INSERT INTO `!prefix_!location` VALUES (402, 'Getty TGN', '7000214', '7003168', 'Lucignano d''Arbia', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.LCGN.DRB.');

INSERT INTO `!prefix_!location` VALUES (403, 'Getty TGN', '7000457', '7003163', 'Firenze', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.FRNZ.');

INSERT INTO `!prefix_!location` VALUES (404, 'Getty TGN', '7000478', '7003162', 'Sansepolcro', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.SNSP.');

INSERT INTO `!prefix_!location` VALUES (405, 'Getty TGN', '7000479', '7003162', 'Monterchi', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.MNTR.');

INSERT INTO `!prefix_!location` VALUES (406, 'Getty TGN', '7003162', '7009760', 'Arezzo', '81161/province', 'Toscana | Italia | Europe', '.ARZ.');

INSERT INTO `!prefix_!location` VALUES (407, 'Getty TGN', '7003163', '7009760', 'Firenze', '81161/province', 'Toscana | Italia | Europe', '.FRNZ.');

INSERT INTO `!prefix_!location` VALUES (408, 'Getty TGN', '7003164', '7009760', 'Livorno', '81161/province', 'Toscana | Italia | Europe', '.LVRN.');

INSERT INTO `!prefix_!location` VALUES (409, 'Getty TGN', '7003165', '7009760', 'Lucca', '81161/province', 'Toscana | Italia | Europe', '.LK.');

INSERT INTO `!prefix_!location` VALUES (410, 'Getty TGN', '7003166', '7009760', 'Pisa', '81161/province', 'Toscana | Italia | Europe', '.PZ.');

INSERT INTO `!prefix_!location` VALUES (411, 'Getty TGN', '7003167', '7009760', 'Pistoia', '81161/province', 'Toscana | Italia | Europe', '.PST.');

INSERT INTO `!prefix_!location` VALUES (412, 'Getty TGN', '7003168', '7009760', 'Siena', '81161/province', 'Toscana | Italia | Europe', '.SN.');

INSERT INTO `!prefix_!location` VALUES (413, 'Getty TGN', '7004014', '7003168', 'San Quirico d''Orcia', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SN.KRK.DRC.');

INSERT INTO `!prefix_!location` VALUES (414, 'Getty TGN', '7004039', '7003167', 'Serravalle Pistoiese', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.SRVL.PSTZ.');

INSERT INTO `!prefix_!location` VALUES (415, 'Getty TGN', '7004249', '1003473', 'Massa', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.MS.');

INSERT INTO `!prefix_!location` VALUES (416, 'Getty TGN', '7004847', '7003127', 'Bologna', '83002/inhabited place', 'Bologna province | Emilia-Romagna | Italia | Europe', '.BLGN.');

INSERT INTO `!prefix_!location` VALUES (417, 'Getty TGN', '7004898', '7003163', 'Monticelli', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MNTC.');

INSERT INTO `!prefix_!location` VALUES (418, 'Getty TGN', '7004955', '7003163', 'Panzano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PNZN.');

INSERT INTO `!prefix_!location` VALUES (419, 'Getty TGN', '7005060', '7003167', 'Pistoia', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.PST.');

INSERT INTO `!prefix_!location` VALUES (420, 'Getty TGN', '7005124', '7003171', 'Orvieto', '83002/inhabited place', 'Terni province | Umbria | Italia | Europe', '.ORVT.');

INSERT INTO `!prefix_!location` VALUES (421, 'Getty TGN', '7006022', '1003460', 'Seggiano', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.SGN.');

INSERT INTO `!prefix_!location` VALUES (422, 'Getty TGN', '7006072', '7003162', 'Arezzo', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.ARZ.');

INSERT INTO `!prefix_!location` VALUES (423, 'Getty TGN', '7006073', '1003460', 'Grosseto', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.GRST.');

INSERT INTO `!prefix_!location` VALUES (424, 'Getty TGN', '7006074', '7003164', 'Livorno', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.LVRN.');

INSERT INTO `!prefix_!location` VALUES (425, 'Getty TGN', '7006075', '7003165', 'Bagni di Lucca', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.BGN.D.LK.');

INSERT INTO `!prefix_!location` VALUES (426, 'Getty TGN', '7006076', '7003165', 'Lucca', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.LK.');

INSERT INTO `!prefix_!location` VALUES (427, 'Getty TGN', '7006077', '1003473', 'Carrara', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.KRR.');

INSERT INTO `!prefix_!location` VALUES (428, 'Getty TGN', '7006080', '1003460', 'Massa Marittima', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.MS.MRTM.');

INSERT INTO `!prefix_!location` VALUES (429, 'Getty TGN', '7006082', '7003166', 'Pisa', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.PZ.');

INSERT INTO `!prefix_!location` VALUES (430, 'Getty TGN', '7006107', '7003162', 'Bibbiena', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.BBN.');

INSERT INTO `!prefix_!location` VALUES (431, 'Getty TGN', '7006109', '7003162', 'Poppi', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.PP.');

INSERT INTO `!prefix_!location` VALUES (432, 'Getty TGN', '7006110', '7003162', 'Borgo alla Collina', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.BRK.AL.KLN.');

INSERT INTO `!prefix_!location` VALUES (433, 'Getty TGN', '7006111', '7003162', 'Stia', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.ST.');

INSERT INTO `!prefix_!location` VALUES (434, 'Getty TGN', '7006112', '7003162', 'Laterina', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.LTRN.');

INSERT INTO `!prefix_!location` VALUES (435, 'Getty TGN', '7006113', '7003162', 'Loro Ciuffenna', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.LR.CFN.');

INSERT INTO `!prefix_!location` VALUES (436, 'Getty TGN', '7006114', '7003162', 'Montevarchi', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.MNTV.');

INSERT INTO `!prefix_!location` VALUES (437, 'Getty TGN', '7006115', '7003162', 'Anghiari', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.ANGR.');

INSERT INTO `!prefix_!location` VALUES (438, 'Getty TGN', '7006116', '7003162', 'Caprese Michelangelo', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.KPRZ.MCHL.');

INSERT INTO `!prefix_!location` VALUES (439, 'Getty TGN', '7006117', '7003162', 'Sestino', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.STN.');

INSERT INTO `!prefix_!location` VALUES (440, 'Getty TGN', '7006118', '7003162', 'Castiglion Fiorentino', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.KSTG.FRNT.');

INSERT INTO `!prefix_!location` VALUES (441, 'Getty TGN', '7006119', '7003162', 'Foiano della Chiana', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.FN.DL.CHN.');

INSERT INTO `!prefix_!location` VALUES (442, 'Getty TGN', '7006120', '7003162', 'Cortona', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.KRTN.');

INSERT INTO `!prefix_!location` VALUES (443, 'Getty TGN', '7006121', '7003162', 'Marciano della Chiana', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.MRCN.DL.CHN.');

INSERT INTO `!prefix_!location` VALUES (444, 'Getty TGN', '7006122', '7003162', 'Lucignano', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.LCGN.');

INSERT INTO `!prefix_!location` VALUES (445, 'Getty TGN', '7006123', '7003162', 'Monte San Savino', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.MNT.SN.SVN.');

INSERT INTO `!prefix_!location` VALUES (446, 'Getty TGN', '7006124', '7000457', 'Galluzzo', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.KLZ.');

INSERT INTO `!prefix_!location` VALUES (447, 'Getty TGN', '7006125', '7000457', 'Castello', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.KSTL.');

INSERT INTO `!prefix_!location` VALUES (448, 'Getty TGN', '7006126', '7000457', 'Careggi', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.KRG.');

INSERT INTO `!prefix_!location` VALUES (449, 'Getty TGN', '7006128', '7000457', 'Settignano', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.STGN.');

INSERT INTO `!prefix_!location` VALUES (450, 'Getty TGN', '7006129', '7000457', 'Coverciano', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.KVRC.');

INSERT INTO `!prefix_!location` VALUES (451, 'Getty TGN', '7006130', '7000457', 'Isolotto', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.ISLT.');

INSERT INTO `!prefix_!location` VALUES (452, 'Getty TGN', '7006131', '7000457', 'Mantignano', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.MNTG.');

INSERT INTO `!prefix_!location` VALUES (453, 'Getty TGN', '7006132', '7000457', 'Montalbano', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.MNTL.');

INSERT INTO `!prefix_!location` VALUES (454, 'Getty TGN', '7006133', '7000457', 'Pieve a Ripoli', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.PV.A.RPL.');

INSERT INTO `!prefix_!location` VALUES (455, 'Getty TGN', '7006134', '7000457', 'Sorgane', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.SRKN.');

INSERT INTO `!prefix_!location` VALUES (456, 'Getty TGN', '7006135', '7000457', 'Bandino', '84217/rione', 'Firenze | Firenze province | Toscana | Italia | Europe', '.BNDN.');

INSERT INTO `!prefix_!location` VALUES (457, 'Getty TGN', '7006136', '7003163', 'Antella', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.ANTL.');

INSERT INTO `!prefix_!location` VALUES (458, 'Getty TGN', '7006137', '7003163', 'Badia a Settimo', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.BD.A.STM.');

INSERT INTO `!prefix_!location` VALUES (459, 'Getty TGN', '7006138', '7003163', 'Bagno a Ripoli', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.BGN.A.RPL.');

INSERT INTO `!prefix_!location` VALUES (460, 'Getty TGN', '7006139', '7003163', 'Campi Bisenzio', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KMP.BZNZ.');

INSERT INTO `!prefix_!location` VALUES (461, 'Getty TGN', '7006140', '7003163', 'Candeli', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KNDL.');

INSERT INTO `!prefix_!location` VALUES (462, 'Getty TGN', '7006141', '7003163', 'Fiesole', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.FZL.');

INSERT INTO `!prefix_!location` VALUES (463, 'Getty TGN', '7006142', '7003163', 'Sesto Fiorentino', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.ST.FRNT.');

INSERT INTO `!prefix_!location` VALUES (464, 'Getty TGN', '7006143', '7003163', 'Scandicci', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SKND.');

INSERT INTO `!prefix_!location` VALUES (465, 'Getty TGN', '7006144', '7003163', 'Impruneta', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.IMPR.');

INSERT INTO `!prefix_!location` VALUES (466, 'Getty TGN', '7006145', '7003163', 'Tavarnelle Val di Pesa', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.TVRN.VL.D.PZ.');

INSERT INTO `!prefix_!location` VALUES (467, 'Getty TGN', '7006146', '7003163', 'Borgo San Lorenzo', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.BRK.SN.LRNZ.');

INSERT INTO `!prefix_!location` VALUES (468, 'Getty TGN', '7006147', '7003163', 'Vicchio', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.VK.');

INSERT INTO `!prefix_!location` VALUES (469, 'Getty TGN', '7006148', '7003163', 'Cavallina', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KVLN.');

INSERT INTO `!prefix_!location` VALUES (470, 'Getty TGN', '7006149', '7003163', 'Cafaggiolo', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KFGL.');

INSERT INTO `!prefix_!location` VALUES (471, 'Getty TGN', '7006151', '7003163', 'Firenzuola', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.FRNZ.');

INSERT INTO `!prefix_!location` VALUES (472, 'Getty TGN', '7006152', '7003163', 'Vaglia', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.VGL.');

INSERT INTO `!prefix_!location` VALUES (473, 'Getty TGN', '7006153', '7003163', 'Palazzuolo sul Senio', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PLZL.SL.SN.');

INSERT INTO `!prefix_!location` VALUES (474, 'Getty TGN', '7006155', '7003163', 'San Piero a Sieve', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.PR.A.SV.');

INSERT INTO `!prefix_!location` VALUES (475, 'Getty TGN', '7006157', '7003163', 'Scarperia', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SKRP.');

INSERT INTO `!prefix_!location` VALUES (476, 'Getty TGN', '7006159', '7003163', 'Calenzano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KLNZ.');

INSERT INTO `!prefix_!location` VALUES (477, 'Getty TGN', '7006161', '7024741', 'Prato', '83002/inhabited place', 'Prato | Toscana | Italia | Europe', '.PRT.');

INSERT INTO `!prefix_!location` VALUES (478, 'Getty TGN', '7006162', '7003163', 'Carmignano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KRMG.');

INSERT INTO `!prefix_!location` VALUES (479, 'Getty TGN', '7006163', '7003163', 'Poggio a Caiano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PG.A.KN.');

INSERT INTO `!prefix_!location` VALUES (480, 'Getty TGN', '7006164', '7003163', 'Montelupo Fiorentino', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MNTL.FRNT.');

INSERT INTO `!prefix_!location` VALUES (481, 'Getty TGN', '7006165', '7003163', 'Lastra a Signa', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.LSTR.A.SGN.');

INSERT INTO `!prefix_!location` VALUES (482, 'Getty TGN', '7006166', '7003163', 'Cerreto Guidi', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.CRT.KD.');

INSERT INTO `!prefix_!location` VALUES (483, 'Getty TGN', '7006167', '7003163', 'Castelfiorentino', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.KSTL.');

INSERT INTO `!prefix_!location` VALUES (484, 'Getty TGN', '7006168', '1006715', 'Capraia', '83002/inhabited place', 'Capraia, Isola di | Livorno province | Toscana | Italia | Europe', '.KPR.');

INSERT INTO `!prefix_!location` VALUES (485, 'Getty TGN', '7006169', '7003163', 'Certaldo', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.CRTL.');

INSERT INTO `!prefix_!location` VALUES (486, 'Getty TGN', '7006170', '7003163', 'Fucecchio', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.FCK.');

INSERT INTO `!prefix_!location` VALUES (487, 'Getty TGN', '7006172', '7003163', 'Montaione', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MNTN.');

INSERT INTO `!prefix_!location` VALUES (488, 'Getty TGN', '7006173', '7003163', 'Vinci', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.VNC.');

INSERT INTO `!prefix_!location` VALUES (489, 'Getty TGN', '7006174', '7003163', 'Signa', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SGN.');

INSERT INTO `!prefix_!location` VALUES (490, 'Getty TGN', '7006175', '1003460', 'Capalbio', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.KPLB.');

INSERT INTO `!prefix_!location` VALUES (491, 'Getty TGN', '7006176', '1003460', 'Orbetello', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.ORBT.');

INSERT INTO `!prefix_!location` VALUES (492, 'Getty TGN', '7006177', '1003460', 'Pitigliano', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.PTGL.');

INSERT INTO `!prefix_!location` VALUES (493, 'Getty TGN', '7006178', '1007191', 'Giglio Castello', '83002/inhabited place', 'Giglio, Isola del | Grosseto province | Toscana | Italia | Europe', '.GL.KSTL.');

INSERT INTO `!prefix_!location` VALUES (494, 'Getty TGN', '7006181', '1003460', 'Sovana', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.SVN.');

INSERT INTO `!prefix_!location` VALUES (495, 'Getty TGN', '7006183', '1003460', 'Arcidosso', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.ARCD.');

INSERT INTO `!prefix_!location` VALUES (496, 'Getty TGN', '7006184', '1003460', 'Campagnatico', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.KMPG.');

INSERT INTO `!prefix_!location` VALUES (497, 'Getty TGN', '7006185', '1003460', 'Istia d''Ombrone', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.IST.DMBR.');

INSERT INTO `!prefix_!location` VALUES (498, 'Getty TGN', '7006186', '7003164', 'Montenero', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.MNTN.');

INSERT INTO `!prefix_!location` VALUES (499, 'Getty TGN', '7006188', '1003460', 'Paganico', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.PKNK.');

INSERT INTO `!prefix_!location` VALUES (500, 'Getty TGN', '7006189', '1003460', 'Vetulonia', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.VTLN.');

INSERT INTO `!prefix_!location` VALUES (501, 'Getty TGN', '7006190', '1003460', 'Triana', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.TRN.');

INSERT INTO `!prefix_!location` VALUES (502, 'Getty TGN', '7006191', '7003164', 'Castiglioncello', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.KSTG.');

INSERT INTO `!prefix_!location` VALUES (503, 'Getty TGN', '7006192', '7003164', 'Campiglia Marittima', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.KMPG.MRTM.');

INSERT INTO `!prefix_!location` VALUES (504, 'Getty TGN', '7006194', '7003164', 'Piombino', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.PMBN.');

INSERT INTO `!prefix_!location` VALUES (505, 'Getty TGN', '7006195', '7003164', 'Populonia', '83002/inhabited place', 'Livorno province | Toscana | Italia | Europe', '.PLN.');

INSERT INTO `!prefix_!location` VALUES (506, 'Getty TGN', '7006196', '7006197', 'Portoferraio', '83002/inhabited place', 'Elba, Isola d'' | Livorno province | Toscana | Italia | Europe', '.PRTF.');

INSERT INTO `!prefix_!location` VALUES (507, 'Getty TGN', '7006197', '7003164', 'Elba, Isola d''', '21471/island', 'Livorno province | Toscana | Italia | Europe', '.ELB.ISL.D.');

INSERT INTO `!prefix_!location` VALUES (508, 'Getty TGN', '7006198', '7003165', 'Capannori', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.KPNR.');

INSERT INTO `!prefix_!location` VALUES (509, 'Getty TGN', '7006200', '7003165', 'Borgo a Mozzano', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.BRK.A.MZN.');

INSERT INTO `!prefix_!location` VALUES (510, 'Getty TGN', '7006201', '7003165', 'Gallicano', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.KLKN.');

INSERT INTO `!prefix_!location` VALUES (511, 'Getty TGN', '7006202', '7003165', 'Camporgiano', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.KMPR.');

INSERT INTO `!prefix_!location` VALUES (512, 'Getty TGN', '7006204', '7003165', 'Camaiore', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.KMR.');

INSERT INTO `!prefix_!location` VALUES (513, 'Getty TGN', '7006205', '7003165', 'Pietrasanta', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.PTRZ.');

INSERT INTO `!prefix_!location` VALUES (514, 'Getty TGN', '7006206', '7003165', 'Seravezza', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.SRVZ.');

INSERT INTO `!prefix_!location` VALUES (515, 'Getty TGN', '7006207', '7003165', 'Viareggio', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.VRG.');

INSERT INTO `!prefix_!location` VALUES (516, 'Getty TGN', '7006242', '7003165', 'Barga', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.BRK.');

INSERT INTO `!prefix_!location` VALUES (517, 'Getty TGN', '7006245', '1003473', 'Aulla', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.AL.');

INSERT INTO `!prefix_!location` VALUES (518, 'Getty TGN', '7006293', '1003473', 'Pontremoli', '83002/inhabited place', 'Massa-Carrara province | Toscana | Italia | Europe', '.PNTR.');

INSERT INTO `!prefix_!location` VALUES (519, 'Getty TGN', '7006294', '7003166', 'Calci', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.KLC.');

INSERT INTO `!prefix_!location` VALUES (520, 'Getty TGN', '7006295', '7003166', 'San Piero a Grado', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.SN.PR.A.GRD.');

INSERT INTO `!prefix_!location` VALUES (521, 'Getty TGN', '7006296', '7003166', 'Vicopisano', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.VKPZ.');

INSERT INTO `!prefix_!location` VALUES (522, 'Getty TGN', '7006299', '7003166', 'Pontedera', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.PNTD.');

INSERT INTO `!prefix_!location` VALUES (523, 'Getty TGN', '7006300', '7003166', 'San Miniato', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.SN.MNT.');

INSERT INTO `!prefix_!location` VALUES (524, 'Getty TGN', '7006301', '7003166', 'Casciana Terme', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.KSCN.TRM.');

INSERT INTO `!prefix_!location` VALUES (525, 'Getty TGN', '7006302', '7003166', 'Palaia', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.PL.');

INSERT INTO `!prefix_!location` VALUES (526, 'Getty TGN', '7006303', '7003166', 'Peccioli', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.PKL.');

INSERT INTO `!prefix_!location` VALUES (527, 'Getty TGN', '7006304', '7003166', 'Castellina Marittima', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.KSTL.MRTM.');

INSERT INTO `!prefix_!location` VALUES (528, 'Getty TGN', '7006305', '7003166', 'Volterra', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.VLTR.');

INSERT INTO `!prefix_!location` VALUES (529, 'Getty TGN', '7006306', '7003167', 'Pescia', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.PSC.');

INSERT INTO `!prefix_!location` VALUES (530, 'Getty TGN', '7006307', '7003167', 'Monsummano Terme', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.MNSM.TRM.');

INSERT INTO `!prefix_!location` VALUES (531, 'Getty TGN', '7006308', '7003167', 'Montecatini Terme', '83002/inhabited place', 'Pistoia province | Toscana | Italia | Europe', '.MNTK.TRM.');

INSERT INTO `!prefix_!location` VALUES (532, 'Getty TGN', '7006309', '7003162', 'Badia Prataglia', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.BD.PRTG.');

INSERT INTO `!prefix_!location` VALUES (533, 'Getty TGN', '7006310', '7003166', 'Cascina', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.KSCN.');

INSERT INTO `!prefix_!location` VALUES (534, 'Getty TGN', '7006312', '7003168', 'Castelnuovo Berardenga', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KSTL.BRDN.');

INSERT INTO `!prefix_!location` VALUES (535, 'Getty TGN', '7006313', '7003168', 'Murlo', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.MRL.');

INSERT INTO `!prefix_!location` VALUES (536, 'Getty TGN', '7006314', '7003168', 'Radda in Chianti', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.RD.IN.CHNT.');

INSERT INTO `!prefix_!location` VALUES (537, 'Getty TGN', '7006315', '7003168', 'Sovicille', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SVCL.');

INSERT INTO `!prefix_!location` VALUES (538, 'Getty TGN', '7006317', '7003168', 'Abbadia San Salvatore', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.ABD.SN.SLVT.');

INSERT INTO `!prefix_!location` VALUES (539, 'Getty TGN', '7006318', '7003168', 'Montalcino', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.MNTL.');

INSERT INTO `!prefix_!location` VALUES (540, 'Getty TGN', '7006319', '7003168', 'Buonconvento', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.BNKN.');

INSERT INTO `!prefix_!location` VALUES (541, 'Getty TGN', '7006320', '7003168', 'Monticchiello', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.MNTK.');

INSERT INTO `!prefix_!location` VALUES (542, 'Getty TGN', '7006321', '7003168', 'Montisi', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.MNTZ.');

INSERT INTO `!prefix_!location` VALUES (543, 'Getty TGN', '7006322', '7003168', 'Pienza', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.PNZ.');

INSERT INTO `!prefix_!location` VALUES (544, 'Getty TGN', '7006323', '7003168', 'San Giovanni d''Asso', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SN.GVN.DS.');

INSERT INTO `!prefix_!location` VALUES (545, 'Getty TGN', '7006324', '7003168', 'Casole d''Elsa', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KZL.DLS.');

INSERT INTO `!prefix_!location` VALUES (546, 'Getty TGN', '7006325', '7003168', 'Monteriggioni', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.MNTR.');

INSERT INTO `!prefix_!location` VALUES (547, 'Getty TGN', '7006326', '7003168', 'Trequanda', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.TRKN.');

INSERT INTO `!prefix_!location` VALUES (548, 'Getty TGN', '7006327', '7003163', 'Barberino Val d''Elsa', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.BRBR.VL.DLS.');

INSERT INTO `!prefix_!location` VALUES (549, 'Getty TGN', '7006328', '7003168', 'Colle di Val d''Elsa', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KL.D.VL.DLS.');

INSERT INTO `!prefix_!location` VALUES (550, 'Getty TGN', '7006329', '7009760', 'Elsa', '21105/river', 'Toscana | Italia | Europe', '.ELS.');

INSERT INTO `!prefix_!location` VALUES (551, 'Getty TGN', '7006330', '7003168', 'Poggibonsi', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.PGBN.');

INSERT INTO `!prefix_!location` VALUES (552, 'Getty TGN', '7006331', '7003168', 'Radicondoli', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.RDKN.');

INSERT INTO `!prefix_!location` VALUES (553, 'Getty TGN', '7006332', '7003168', 'Staggia', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.STG.');

INSERT INTO `!prefix_!location` VALUES (554, 'Getty TGN', '7006333', '7003168', 'Asciano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.ASCN.');

INSERT INTO `!prefix_!location` VALUES (555, 'Getty TGN', '7006334', '7003168', 'San Gimignano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SN.GMGN.');

INSERT INTO `!prefix_!location` VALUES (556, 'Getty TGN', '7006335', '7003168', 'Sarteano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SRTN.');

INSERT INTO `!prefix_!location` VALUES (557, 'Getty TGN', '7006336', '7003168', 'Scrofiano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SCRF.');

INSERT INTO `!prefix_!location` VALUES (558, 'Getty TGN', '7006337', '7003168', 'Serre di Rapolano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SR.D.RPLN.');

INSERT INTO `!prefix_!location` VALUES (559, 'Getty TGN', '7006338', '7003168', 'Sinalunga', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SNLN.');

INSERT INTO `!prefix_!location` VALUES (560, 'Getty TGN', '7006339', '7003168', 'Torrita di Siena', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.TRT.D.SN.');

INSERT INTO `!prefix_!location` VALUES (561, 'Getty TGN', '7006341', '7003168', 'Cetona', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.CTN.');

INSERT INTO `!prefix_!location` VALUES (562, 'Getty TGN', '7006344', '7003168', 'Chiusi', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.CHZ.');

INSERT INTO `!prefix_!location` VALUES (563, 'Getty TGN', '7006345', '7003168', 'Montepulciano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.MNTP.');

INSERT INTO `!prefix_!location` VALUES (564, 'Getty TGN', '7006346', '7003168', 'Rapolano Terme', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.RPLN.TRM.');

INSERT INTO `!prefix_!location` VALUES (565, 'Getty TGN', '7006520', '7003163', 'Volognano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.VLGN.');

INSERT INTO `!prefix_!location` VALUES (566, 'Getty TGN', '7006522', '7009760', 'Casentino', '22101/general region', 'Toscana | Italia | Europe', '.KZNT.');

INSERT INTO `!prefix_!location` VALUES (567, 'Getty TGN', '7006523', '7003163', 'Cercina', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.CRCN.');

INSERT INTO `!prefix_!location` VALUES (568, 'Getty TGN', '7006524', '7003162', 'Montelungo', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.MNTL.');

INSERT INTO `!prefix_!location` VALUES (569, 'Getty TGN', '7006526', '7003168', 'Chianciano Terme', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.CHNC.TRM.');

INSERT INTO `!prefix_!location` VALUES (570, 'Getty TGN', '7006528', '7003163', 'Figline Valdarno', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.FGLN.VLDR.');

INSERT INTO `!prefix_!location` VALUES (571, 'Getty TGN', '7006530', '7003163', 'Greve', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.GRV.');

INSERT INTO `!prefix_!location` VALUES (572, 'Getty TGN', '7006531', '7009760', 'Mugello', '22101/general region', 'Toscana | Italia | Europe', '.MGL.');

INSERT INTO `!prefix_!location` VALUES (573, 'Getty TGN', '7006533', '7009760', 'Chiana, Val di', '22101/general region', 'Toscana | Italia | Europe', '.CHN.VL.D.');

INSERT INTO `!prefix_!location` VALUES (574, 'Getty TGN', '7006537', '7003162', 'San Giovanni Valdarno', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.SN.GVN.VLDR.');

INSERT INTO `!prefix_!location` VALUES (575, 'Getty TGN', '7006539', '7003163', 'Falterona, Monte', '21430/mountain', 'Firenze province | Toscana | Italia | Europe', '.FLTR.MNT.');

INSERT INTO `!prefix_!location` VALUES (576, 'Getty TGN', '7006543', '7003163', 'Faltugnano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.FLTG.');

INSERT INTO `!prefix_!location` VALUES (577, 'Getty TGN', '7006544', '7003163', 'Rufina', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.RFN.');

INSERT INTO `!prefix_!location` VALUES (578, 'Getty TGN', '7006547', '7003163', 'Legnaia', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.LGN.');

INSERT INTO `!prefix_!location` VALUES (579, 'Getty TGN', '7006548', '7003163', 'Montegufoni', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.MNTK.');

INSERT INTO `!prefix_!location` VALUES (580, 'Getty TGN', '7006555', '7003163', 'Petriolo', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PTRL.');

INSERT INTO `!prefix_!location` VALUES (581, 'Getty TGN', '7006556', '7003163', 'Pontassieve', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PNTS.');

INSERT INTO `!prefix_!location` VALUES (582, 'Getty TGN', '7006565', '7003163', 'Vincigliata', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.VNCG.');

INSERT INTO `!prefix_!location` VALUES (583, 'Getty TGN', '7006573', '7003163', 'Pelago', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PLK.');

INSERT INTO `!prefix_!location` VALUES (584, 'Getty TGN', '7006574', '7003163', 'Pozzolatico', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PZLT.');

INSERT INTO `!prefix_!location` VALUES (585, 'Getty TGN', '7006575', '7003163', 'Empoli', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.EMPL.');

INSERT INTO `!prefix_!location` VALUES (586, 'Getty TGN', '7006579', '7003163', 'Vespignano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.VSPG.');

INSERT INTO `!prefix_!location` VALUES (587, 'Getty TGN', '7006581', '7003163', 'Vallombrosa', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.VLMB.');

INSERT INTO `!prefix_!location` VALUES (588, 'Getty TGN', '7006582', '7009760', 'Maremma', '22101/general region', 'Toscana | Italia | Europe', '.MRM.');

INSERT INTO `!prefix_!location` VALUES (589, 'Getty TGN', '7006584', '1003460', 'Roccalbegna', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.RKLB.');

INSERT INTO `!prefix_!location` VALUES (590, 'Getty TGN', '7006588', '1003460', 'Talamone', '83210/deserted settlement', 'Grosseto province | Toscana | Italia | Europe', '.TLMN.');

INSERT INTO `!prefix_!location` VALUES (591, 'Getty TGN', '7006589', '7003163', 'Futa, Passo della', '21433/pass', 'Firenze province | Toscana | Italia | Europe', '.FT.PS.DL.');

INSERT INTO `!prefix_!location` VALUES (592, 'Getty TGN', '7006590', '7003166', 'Canneto', '83002/inhabited place', 'Pisa province | Toscana | Italia | Europe', '.KNT.');

INSERT INTO `!prefix_!location` VALUES (593, 'Getty TGN', '7006591', '7003168', 'Lecceto', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.LKT.');

INSERT INTO `!prefix_!location` VALUES (594, 'Getty TGN', '7006592', '7003163', 'San Casciano in Val di Pesa', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.KSCN.IN.VL.D.PZ.');

INSERT INTO `!prefix_!location` VALUES (595, 'Getty TGN', '7007728', '7003168', 'Castiglione d''Orcia', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KSTG.DRC.');

INSERT INTO `!prefix_!location` VALUES (596, 'Getty TGN', '7007731', '7003162', 'Cam$00aldoli', '83002/inhabited place', 'Arezzo province | Toscana | Italia | Europe', '.KMLD.');

INSERT INTO `!prefix_!location` VALUES (597, 'Getty TGN', '7008397', '7003163', 'Incisa in Val d''Arno', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.INCZ.IN.VL.DRN.');

INSERT INTO `!prefix_!location` VALUES (598, 'Getty TGN', '7008555', '7003163', 'Reggello', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.RGL.');

INSERT INTO `!prefix_!location` VALUES (599, 'Getty TGN', '7008900', '7003163', 'San Godenzo', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.SN.KDNZ.');

INSERT INTO `!prefix_!location` VALUES (600, 'Getty TGN', '7009080', '1003460', 'Albegna', '21105/river', 'Grosseto province | Toscana | Italia | Europe', '.ALBG.');

INSERT INTO `!prefix_!location` VALUES (601, 'Getty TGN', '7009760', '1000080', 'Toscana', '81165/region', 'Italia | Europe', '.TSKN.');

INSERT INTO `!prefix_!location` VALUES (602, 'Getty TGN', '7009957', '7003163', 'Rignano sull''Arno', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.RGN.SLRN.');

INSERT INTO `!prefix_!location` VALUES (603, 'Getty TGN', '7010020', '7003163', 'Pratolino', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.PRTL.');

INSERT INTO `!prefix_!location` VALUES (604, 'Getty TGN', '7010381', '1003460', 'Cosa', '83210/deserted settlement', 'Grosseto province | Toscana | Italia | Europe', '.KZ.');

INSERT INTO `!prefix_!location` VALUES (605, 'Getty TGN', '7010394', '7003168', 'Monte Amiata', '21430/mountain', 'Siena province | Toscana | Italia | Europe', '.MNT.AMT.');

INSERT INTO `!prefix_!location` VALUES (606, 'Getty TGN', '7011179', '7003168', 'Siena', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SN.');

INSERT INTO `!prefix_!location` VALUES (607, 'Getty TGN', '7011181', '1003460', 'Sorano', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.SRN.');

INSERT INTO `!prefix_!location` VALUES (608, 'Getty TGN', '7015413', '1003460', 'Rusellae', '83210/deserted settlement', 'Grosseto province | Toscana | Italia | Europe', '.RZL.');

INSERT INTO `!prefix_!location` VALUES (609, 'Getty TGN', '7015529', '1003460', 'Argentario, Monte', '21430/mountain', 'Grosseto province | Toscana | Italia | Europe', '.ARGN.MNT.');

INSERT INTO `!prefix_!location` VALUES (610, 'Getty TGN', '7017499', '7003168', 'Casciano', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KSCN.');

INSERT INTO `!prefix_!location` VALUES (611, 'Getty TGN', '7017500', '1003460', 'Borgo Carige', '83002/inhabited place', 'Grosseto province | Toscana | Italia | Europe', '.BRK.KRG.');

INSERT INTO `!prefix_!location` VALUES (612, 'Getty TGN', '7017853', '7009760', 'Chianti', '22101/general region', 'Toscana | Italia | Europe', '.CHNT.');

INSERT INTO `!prefix_!location` VALUES (613, 'Getty TGN', '7018786', '7003168', 'Monte Labbro', '21430/mountain', 'Siena province | Toscana | Italia | Europe', '.MNT.LBR.');

INSERT INTO `!prefix_!location` VALUES (614, 'Getty TGN', '7023825', '7003168', 'Castellina in Chianti', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.KSTL.IN.CHNT.');

INSERT INTO `!prefix_!location` VALUES (615, 'Getty TGN', '7023893', '7003168', 'San Casciano dei Bagni', '83002/inhabited place', 'Siena province | Toscana | Italia | Europe', '.SN.KSCN.D.BGN.');

INSERT INTO `!prefix_!location` VALUES (616, 'Getty TGN', '7023894', '7003168', 'San Galgano, Abbazia di', '54414/abbey', 'Siena province | Toscana | Italia | Europe', '.SN.KLKN.ABZ.D.');

INSERT INTO `!prefix_!location` VALUES (617, 'Getty TGN', '7024113', '7023981', 'Etruria', '81031/former group of nations/states/cities', 'Italian Peninsula | Europe', '.ETR.');

INSERT INTO `!prefix_!location` VALUES (618, 'Getty TGN', '7024602', '7003164', 'Peraiola', '21471/island', 'Livorno province | Toscana | Italia | Europe', '.PRL.');

INSERT INTO `!prefix_!location` VALUES (619, 'Getty TGN', '7024741', '7009760', 'Prato', '81161/province', 'Toscana | Italia | Europe', '.PRT.');

INSERT INTO `!prefix_!location` VALUES (620, 'Getty TGN', '7028107', '7003168', 'Siena, Le Masse di', '21438/hills', 'Siena province | Toscana | Italia | Europe', '.SN.L.MS.D.');

INSERT INTO `!prefix_!location` VALUES (621, 'Getty TGN', '7029392', '1000000', 'World', '10003/facet', 'Top of the TGN hierarchy', '.WRL.');

INSERT INTO `!prefix_!location` VALUES (622, 'Getty TGN', '7029393', '7003163', 'Anchiano', '83002/inhabited place', 'Firenze province | Toscana | Italia | Europe', '.ANCH.');

INSERT INTO `!prefix_!location` VALUES (623, 'Getty TGN', '7029473', '7003168', 'La Piana', '83210/deserted settlement', 'Siena province | Toscana | Italia | Europe', '.L.PN.');

INSERT INTO `!prefix_!location` VALUES (624, 'Getty TGN', '7029528', '7003165', 'Minucciano', '83002/inhabited place', 'Lucca province | Toscana | Italia | Europe', '.MNKN.');

INSERT INTO `!prefix_!location` VALUES (625, 'Getty TGN', '7030235', '7006192', 'Rocco San Silvestro', '51223/fortification', 'Campiglia Marittima | Livorno province | Toscana | Italia | Europe', '.RK.SN.SLVS.');
