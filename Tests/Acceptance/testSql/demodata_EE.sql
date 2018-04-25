# Making EN as default lang.
UPDATE `oxconfig` SET `OXVARVALUE` = 0x07 WHERE `OXVARNAME` = 'sDefaultLang';

# Add test user data.
REPLACE INTO `oxuser`
SET
  `OXID` = 'oeecondatestuser',
  `OXACTIVE` = 1,
  `OXRIGHTS` = 'user',
  `OXSHOPID` = 1,
  `OXUSERNAME` = 'testing_account@oxid-esales.local',
  `OXPASSWORD` = '13d726b9144af35f87b353e86185246fa4dc763f059d01e482d1360765ff3db96ec143df98c14df28b8d9414d40b5f32d090280c7d405a90d27de7f414437d7a',
  `OXPASSSALT` = '8f9c890adba62e6b8610544792b9eecc',
  `OXCUSTNR` = 1000,
  `OXFNAME` = 'FirstName',
  `OXLNAME` = 'LastName',
  `OXSTREET` = 'StreetName',
  `OXSTREETNR` = 'StreetNr',
  `OXZIP` = '79098',
  `OXCITY` = 'Freiburg',
  `OXCOUNTRYID` = 'a7c40f631fc920687.20179984',
  `OXCREATE` = '2000-01-01 00:00:00',
  `OXREGISTER` = '2000-01-01 00:00:00',
  `OXBIRTHDATE` = '2000-01-01';


# Add products demo data.
REPLACE INTO `oxarticles`
SET
  `OXID` = '1000',
  `OXMAPID` = 101,
  `OXSHOPID` = 1,
  `OXACTIVE` = 1,
  `OXARTNUM` = '1000',
  `OXTITLE` = 'Test product',
  `OXSHORTDESC` = 'Test product short desc',
  `OXPRICE` = 10,
  `OXUNITNAME` = 'kg',
  `OXUNITQUANTITY` = 2,
  `OXVAT` = NULL,
  `OXWEIGHT` = 24,
  `OXSTOCK` = 15,
  `OXSTOCKFLAG` = 1,
  `OXINSERT` = '2008-02-04',
  `OXTIMESTAMP` = '2008-02-04 17:07:29',
  `OXLENGTH` = 1,
  `OXWIDTH` = 2,
  `OXHEIGHT` = 2,
  `OXSEARCHKEYS` = 'search1000',
  `OXISSEARCH` = 1,
  `OXVARMINPRICE` = 50,
  `OXTITLE_1` = 'Test product',
  `OXSHORTDESC_1` = 'Test product short desc',
  `OXSUBCLASS` = 'oxarticle',
  `OXVPE` = 1;

REPLACE INTO `oxarticles2shop`
SET
  `OXSHOPID` = 1,
  `OXMAPOBJECTID` = 101;

# Add categories demo data.
REPLACE INTO `oxcategories`
SET
  `OXID` = 'oeecondacategoryid',
  `OXMAPID` = 101,
  `OXPARENTID` = 'oxrootid',
  `OXLEFT` = 1,
  `OXRIGHT` = 4,
  `OXROOTID` = 'oeecondacategoryid',
  `OXSORT` = 1,
  `OXACTIVE` = 1,
  `OXHIDDEN` = 0,
  `OXSHOPID` = 1,
  `OXTITLE` = 'Test category',
  `OXDESC` = 'Test category desc',
  `OXLONGDESC` = '<p>Category long desc</p>',
  `OXDEFSORT` = 'oxartnum',
  `OXDEFSORTMODE` = 0,
  `OXACTIVE_1` = 1,
  `OXTITLE_1` = 'Test category',
  `OXDESC_1` = 'Test category desc',
  `OXLONGDESC_1` = '<p>Category 0 long desc</p>',
  `OXVAT` = NULL,
  `OXSKIPDISCOUNTS` = 0,
  `OXSHOWSUFFIX` = 1;


REPLACE INTO `oxcategories2shop`
SET
  `OXSHOPID` = 1,
  `OXMAPOBJECTID` = 101;

REPLACE INTO `oxobject2category`
SET
  `OXID` = 'oeecondaid',
  `OXSHOPID` =  1,
  `OXOBJECTID` = '1000',
  `OXCATNID` = 'oeecondacategoryid',
  `OXPOS` = 0,
  `OXTIME` = 1202134861;
