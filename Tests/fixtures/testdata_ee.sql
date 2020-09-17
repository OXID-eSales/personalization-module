SET @@session.sql_mode = '';

# Activate en and de languages
UPDATE `oxconfig` SET `OXVARVALUE` = 'a:2:{s:2:"de";a:3:{s:6:"baseId";i:0;s:6:"active";s:1:"1";s:4:"sort";s:1:"1";}s:2:"en";a:3:{s:6:"baseId";i:1;s:6:"active";s:1:"1";s:4:"sort";s:1:"2";}}' WHERE `OXVARNAME` = 'aLanguageParams';

#set country for default user
UPDATE oxuser SET oxcountryid = 'a7c40f631fc920687.20179984' where oxid='oxdefaultadmin';

#
# Data for table `oxarticles`
#

INSERT IGNORE INTO `oxarticles` (`OXID`, `OXSHOPID`, `OXPARENTID`, `OXACTIVE`, `OXACTIVEFROM`, `OXACTIVETO`, `OXARTNUM`, `OXEAN`, `OXDISTEAN`, `OXMPN`, `OXTITLE`, `OXSHORTDESC`, `OXPRICE`, `OXBLFIXEDPRICE`, `OXPRICEA`, `OXPRICEB`, `OXPRICEC`, `OXBPRICE`, `OXTPRICE`, `OXUNITNAME`, `OXUNITQUANTITY`, `OXEXTURL`, `OXURLDESC`, `OXURLIMG`, `OXVAT`, `OXTHUMB`, `OXICON`, `OXPIC1`, `OXPIC2`, `OXPIC3`, `OXPIC4`, `OXPIC5`, `OXPIC6`, `OXPIC7`, `OXPIC8`, `OXPIC9`, `OXPIC10`, `OXPIC11`, `OXPIC12`, `OXWEIGHT`, `OXSTOCK`, `OXSTOCKFLAG`, `OXSTOCKTEXT`, `OXNOSTOCKTEXT`, `OXDELIVERY`, `OXINSERT`, `OXTIMESTAMP`, `OXLENGTH`, `OXWIDTH`, `OXHEIGHT`, `OXFILE`, `OXSEARCHKEYS`, `OXTEMPLATE`, `OXQUESTIONEMAIL`, `OXISSEARCH`, `OXISCONFIGURABLE`, `OXVARNAME`, `OXVARSTOCK`, `OXVARCOUNT`, `OXVARSELECT`, `OXVARMINPRICE`, `OXVARMAXPRICE`, `OXVARNAME_1`, `OXVARSELECT_1`, `OXVARNAME_2`, `OXVARSELECT_2`, `OXVARNAME_3`, `OXVARSELECT_3`, `OXTITLE_1`, `OXSHORTDESC_1`, `OXURLDESC_1`, `OXSEARCHKEYS_1`, `OXTITLE_2`, `OXSHORTDESC_2`, `OXURLDESC_2`, `OXSEARCHKEYS_2`, `OXTITLE_3`, `OXSHORTDESC_3`, `OXURLDESC_3`, `OXSEARCHKEYS_3`, `OXFOLDER`, `OXSUBCLASS`, `OXSTOCKTEXT_1`, `OXSTOCKTEXT_2`, `OXSTOCKTEXT_3`, `OXNOSTOCKTEXT_1`, `OXNOSTOCKTEXT_2`, `OXNOSTOCKTEXT_3`, `OXSORT`, `OXSOLDAMOUNT`, `OXNONMATERIAL`, `OXFREESHIPPING`, `OXREMINDACTIVE`, `OXREMINDAMOUNT`, `OXAMITEMID`, `OXAMTASKID`, `OXVENDORID`, `OXMANUFACTURERID`, `OXSKIPDISCOUNTS`, `OXORDERINFO`, `OXPIXIEXPORT`, `OXPIXIEXPORTED`, `OXVPE`, `OXRATING`, `OXRATINGCNT`, `OXMINDELTIME`, `OXMAXDELTIME`, `OXDELTIMEUNIT`, `OXUPDATEPRICE`, `OXUPDATEPRICEA`, `OXUPDATEPRICEB`, `OXUPDATEPRICEC`, `OXUPDATEPRICETIME`, `OXISDOWNLOADABLE`) VALUES
('1952', 1, '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1952', '', '', '', 'Hangover Pack LITTLE HELPER', '', 6, 0, 0, 0, 0, 0, 0, '', 0, '', '', '', NULL, '1952_th.jpg', '1952_ico.jpg',  '1952_p1.jpg', '', '', '', '', '', '', '', '', '', '', '', 0, 22, 1, 'Lieferzeit 1-2 Tage', 'Lieferzeit 1-2 Wochen', '0000-00-00', '2005-07-28', '2010-02-18 17:29:27', 0, 0, 0, '', '', '', '', 1, 0, '', 0, 0, '', 6, 0, '', '', '', '', '', '', 'Hangover Set LITTLE HELPER', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Will be shipped in 24-48 hours', '', '', 'Will be shipped in 7-14 days', '', '', 0, 10, 0, 0, 0, 0, '', '', '', '', 0, '', 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '0000-00-00 00:00:00', 0),
('1952_variant_1', 1, '1952', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1952_variant_1', '', '', '', 'Hangover Pack LITTLE HELPER', '', 6, 0, 0, 0, 0, 0, 0, '', 0, '', '', '', NULL, '1952_th.jpg', '1952_ico.jpg',  '1952_p1.jpg', '', '', '', '', '', '', '', '', '', '', '', 0, 22, 1, 'Lieferzeit 1-2 Tage', 'Lieferzeit 1-2 Wochen', '0000-00-00', '2005-07-28', '2010-02-18 17:29:27', 0, 0, 0, '', '', '', '', 1, 0, '', 0, 0, '', 6, 0, '', '', '', '', '', '', 'Hangover Set LITTLE HELPER', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Will be shipped in 24-48 hours', '', '', 'Will be shipped in 7-14 days', '', '', 0, 10, 0, 0, 0, 0, '', '', '', '', 0, '', 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '0000-00-00 00:00:00', 0),
('2024', 1, '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2024', '', '', '', 'Popcornschale PINK', '', 11, 0, 0, 0, 0, 0, 0, '', 0, '', '', '', NULL, '2024_th.jpg', '2024_ico.jpg',  '2024_p1.jpg', '', '', '', '', '', '', '', '', '', '', '', 0, 7, 1, 'Lieferzeit 1-2 Tage', 'Lieferzeit 1-2 Wochen', '0000-00-00', '2005-07-28', '2010-02-18 17:29:27', 0, 0, 0, '', '', '', '', 1, 0, '', 0, 0, '', 11, 0, '', '', '', '', '', '', 'Popcorn Bowl PINK', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Will be shipped in 24-48 hours', '', '', 'Will be shipped in 7-14 days', '', '', 0, 20, 0, 0, 0, 0, '', '', '', '', 0, '', 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '0000-00-00 00:00:00', 0),
('1849', 1, '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1849', '', '', '', 'Bar "Butler" 6 BOTTLES', '', 89.9, 0, 0, 0, 0, 94, 94, '', 0, '', '', '', NULL, '1849_th.jpg', '1849_ico.jpg',  '1849_p1.jpg', '', '', '', '', '', '', '', '', '', '', '', 0, 6, 1, 'Lieferzeit 1-2 Tage', 'Lieferzeit 1-2 Wochen', '0000-00-00', '2005-07-28', '2010-02-18 17:29:27', 0, 0, 0, '', '', '', '', 1, 0, '', 0, 0, '', 89.9, 0, '', '', '', '', '', '', 'Bar "Butler" 6 BOTTLES', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Will be shipped in 24-48 hours', '', '', 'Will be shipped in 7-14 days', '', '', 0, 0, 0, 0, 0, 0, '', '', '', '', 0, '', 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '0000-00-00 00:00:00', 0),
('8888', 2, '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '8888', '', '', '', 'Bar "Butler" 6 BOTTLES', '', 89.9, 0, 0, 0, 0, 94, 94, '', 0, '', '', '', NULL, '1849_th.jpg', '1849_ico.jpg',  '1849_p1.jpg', '', '', '', '', '', '', '', '', '', '', '', 0, 6, 1, 'Lieferzeit 1-2 Tage', 'Lieferzeit 1-2 Wochen', '0000-00-00', '2005-07-28', '2010-02-18 17:29:27', 0, 0, 0, '', '', '', '', 1, 0, '', 0, 0, '', 89.9, 0, '', '', '', '', '', '', 'Bar "Butler" 6 BOTTLES', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Will be shipped in 24-48 hours', '', '', 'Will be shipped in 7-14 days', '', '', 0, 0, 0, 0, 0, 0, '', '', '', '', 0, '', 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, '', 0, 0, 0, 0, '0000-00-00 00:00:00', 0);

INSERT IGNORE INTO `oxartextends` (`OXID`, `OXLONGDESC`, `OXLONGDESC_1`, `OXLONGDESC_2`, `OXLONGDESC_3`) VALUES
('1952', '<div>Erste Hilfe f�r den Morgen danach. Einfach Eis in den Beutel f�llen und ab damit auf das schmerzende Haupt! </div>\r\n<div>&nbsp;</div>\r\n<div>Durchmesser: 20 cm</div>\r\n<div>&nbsp;</div>\r\n<div><span style="color: #666666;"><strong>Bezugshinweis:</strong> bei Interesse k�nnen Sie dieses Produkt bei <a href="http://www.desaster.com/" style="color: #666666;">www.desaster.com</a> erwerben.</span></div>', '<P>First aid for the morning after. Fill ice in the bag and put it to your hurting head.</P>\r\n<P>Measures 7.9 inch</P>\r\n\r\n<P><STRONG><FONT COLOR="#666666">Where to\r\nbuy:</FONT></STRONG><FONT COLOR="#666666"> If you are interested you\r\ncan purchase this product at <A HREF="http://www.desaster.com/">www.desaster.com</A></FONT></P>', '', ''),
('2024', '<div>Riesengro�e pinke Schale im Popcorn-Design. </div>\r\n<div>&nbsp;</div>\r\n<div>Durchmesser: 25,5 cm</div>\r\n<div>&nbsp;</div>\r\n<div><span style="color: #666666;"><strong>Bezugshinweis:</strong> bei Interesse k�nnen Sie dieses Produkt bei <a href="http://www.desaster.com/" style="color: #666666;">www.desaster.com</a> erwerben.</span></div>', '<P>A huge pink bowl in a fancy popcorn design.</P>\r\n<P>Diameter: 10 inches</P>\r\n\r\n<P><STRONG><FONT COLOR="#666666">Where to\r\nbuy:</FONT></STRONG><FONT COLOR="#666666"> If you are interested you\r\ncan purchase this product at <A HREF="http://www.desaster.com/">www.desaster.com</A></FONT></P>', '', ''),
('1849', '<div>F�r 6 Flaschen. </div>\r\n<div>&nbsp;</div>\r\n<div>H�he: 52 cm <br>\r\nMaterial: Chrom</div>\r\n<div>&nbsp;</div>\r\n<div><span style="color: #666666;"><strong>Bezugshinweis:</strong> bei Interesse k�nnen Sie dieses Produkt bei <a href="http://www.desaster.com/" style="color: #666666;">www.desaster.com</a> erwerben.</span></div>', '<P>A bar butler for six bottles.</P>\r\n<P>Height: 20.5 inch<BR>\r\nMaterial: chrome</P>\r\n\r\n<P><STRONG><FONT COLOR="#666666">Where to\r\nbuy:</FONT></STRONG><FONT COLOR="#666666"> If you are interested you\r\ncan purchase this product at <A HREF="http://www.desaster.com/">www.desaster.com</A></FONT></P>', '', '');


#
# Data for table `oxobject2category`
#
INSERT IGNORE INTO `oxobject2category` (`OXID`, `OXSHOPID`, `OXOBJECTID`, `OXCATNID`, `OXPOS`, `OXTIME`) VALUES
('8a142c3e7c2424325.54593359', 1, '1952', '8a142c3e4143562a5.46426637', 1, 1120135167),
('8a142c3e7b36eece1.24875909', 1, '2024', '8a142c3e4143562a5.46426637', 0, 1120135152),
('8a142c3e71b6fb3f6.40220233', 1, '1849', '8a142c3e49b5a80c1.23676990', 0, 1120135000),
('object2categoryidforsubshops', 2, '8888', 'subshopcategoryid', 0, 1120135000),
('8a142c3e71b6fb3f6.40240433', 1, '1849', '8a142c3e4143562a5.46426637', 3, 1120135010);


INSERT IGNORE INTO `oxcategories` (`OXID`, `OXPARENTID`, `OXLEFT`, `OXRIGHT`, `OXROOTID`, `OXSORT`, `OXACTIVE`, `OXHIDDEN`, `OXSHOPID`, `OXTITLE`, `OXDESC`, `OXLONGDESC`, `OXTHUMB`, `OXTHUMB_1`, `OXTHUMB_2`, `OXTHUMB_3`, `OXEXTLINK`, `OXTEMPLATE`, `OXDEFSORT`, `OXDEFSORTMODE`, `OXPRICEFROM`, `OXPRICETO`, `OXACTIVE_1`, `OXTITLE_1`, `OXDESC_1`, `OXLONGDESC_1`, `OXACTIVE_2`, `OXTITLE_2`, `OXDESC_2`, `OXLONGDESC_2`, `OXACTIVE_3`, `OXTITLE_3`, `OXDESC_3`, `OXLONGDESC_3`, `OXICON`, `OXPROMOICON`, `OXVAT`, `OXSKIPDISCOUNTS`, `OXSHOWSUFFIX`) VALUES
('8a142c3e4143562a5.46426637', 'oxrootid', 1, 10, '8a142c3e4143562a5.46426637', 9999, 1, 0, 1, 'Geschenke', '', '', '', '', '', '', '', '', '', 0, 0, 0, 1, 'Gifts', '', '', 1, '', '', '', 0, '', '', '', '', '', NULL, 0, 1),
('8a142c3e44ea4e714.31136811', '8a142c3e4143562a5.46426637', 2, 5, '8a142c3e4143562a5.46426637', 9999, 1, 0, 1, 'Wohnen', 'Man kann nie zu reich, zu sch�n oder zu m�biliert sein!', '', '', '', '', '', '', '', '', 0, 0, 0, 1, 'Living', 'You can never be too rich, too pretty, or have too nice furniture!', '', 1, '', '', '', 0, '', '', '', '', '', NULL, 0, 1),
('8a142c3e49b5a80c1.23676990', '8a142c3e4143562a5.46426637', 6, 7, '8a142c3e4143562a5.46426637', 9999, 1, 0, 1, 'Bar-Equipment', 'Stilvoll saufen!', '<div>Hier finden Sie Bar-Equipment f�r Party und private Anl�sse, ebenso wie f�r den professionellen Einsatz.</div>', 'bar_tc.jpg', '', '', '', '', '', '', 0, 0, 0, 1, 'Bar Equipment', 'drink in style', '<div>In this category you will find bar equipment for parties and private occasions as well as for professional use.</div>', 1, '', '', '', 0, '', '', '', '', '', NULL, 0, 1),
('8a142c3e4d3253c95.46563530', '8a142c3e4143562a5.46426637', 8, 9, '8a142c3e4143562a5.46426637', 9999, 1, 0, 1, 'Fantasy', '', '', '', '', '', '', '', '', '', 0, 0, 0, 1, 'Fantasy', '', '', 1, '', '', '', 0, '', '', '', '', '', NULL, 0, 1),
('8a142c3e60a535f16.78077188', '8a142c3e44ea4e714.31136811', 3, 4, '8a142c3e4143562a5.46426637', 9999, 1, 0, 1, 'Uhren', '', '', 'uhren3_tc.jpg', '', '', '', '', '', '', 0, 0, 0, 1, 'Clocks', '', '', 1, '', '', '', 0, '', '', '', '', '', NULL, 0, 1),
('subshopcategoryid', 'oxrootid', 3, 4, 'subshopcategoryid', 9999, 1, 0, 2, 'Geschenke', '', '', '', '', '', '', '', '', '', 0, 0, 0, 1, 'Gifts', '', '', 1, '', '', '', 0, '', '', '', '', '', NULL, 0, 1);

#
# adding default inheritance
#
INSERT INTO `oxcategories2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
  (1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (2, 6);

INSERT INTO `oxarticles2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
  (1, 1), (1, 2), (1, 3), (1, 4), (2, 5);

INSERT INTO `oxshops` (`OXID`, `OXPARENTID`, `OXACTIVE`, `OXISINHERITED`, `OXISMULTISHOP`, `OXISSUPERSHOP`, `OXPRODUCTIVE`, `OXDEFCURRENCY`, `OXDEFLANGUAGE`, `OXNAME`, `OXTITLEPREFIX`, `OXTITLEPREFIX_1`, `OXTITLEPREFIX_2`, `OXTITLEPREFIX_3`, `OXTITLESUFFIX`, `OXTITLESUFFIX_1`, `OXTITLESUFFIX_2`, `OXTITLESUFFIX_3`, `OXSTARTTITLE`, `OXSTARTTITLE_1`, `OXSTARTTITLE_2`, `OXSTARTTITLE_3`, `OXINFOEMAIL`, `OXORDEREMAIL`, `OXOWNEREMAIL`, `OXORDERSUBJECT`, `OXREGISTERSUBJECT`, `OXFORGOTPWDSUBJECT`, `OXSENDEDNOWSUBJECT`, `OXORDERSUBJECT_1`, `OXREGISTERSUBJECT_1`, `OXFORGOTPWDSUBJECT_1`, `OXSENDEDNOWSUBJECT_1`, `OXORDERSUBJECT_2`, `OXREGISTERSUBJECT_2`, `OXFORGOTPWDSUBJECT_2`, `OXSENDEDNOWSUBJECT_2`, `OXORDERSUBJECT_3`, `OXREGISTERSUBJECT_3`, `OXFORGOTPWDSUBJECT_3`, `OXSENDEDNOWSUBJECT_3`, `OXSMTP`, `OXSMTPUSER`, `OXSMTPPWD`, `OXCOMPANY`, `OXSTREET`, `OXZIP`, `OXCITY`, `OXCOUNTRY`, `OXBANKNAME`, `OXBANKNUMBER`, `OXBANKCODE`, `OXVATNUMBER`, `OXTAXNUMBER`, `OXBICCODE`, `OXIBANNUMBER`, `OXFNAME`, `OXLNAME`, `OXTELEFON`, `OXTELEFAX`, `OXURL`, `OXDEFCAT`, `OXHRBNR`, `OXCOURT`, `OXADBUTLERID`, `OXAFFILINETID`, `OXSUPERCLICKSID`, `OXAFFILIWELTID`, `OXAFFILI24ID`, `OXEDITION`, `OXVERSION`, `OXSEOACTIVE`, `OXSEOACTIVE_1`, `OXSEOACTIVE_2`, `OXSEOACTIVE_3`, `OXTIMESTAMP`, `OXSERIAL`) VALUES
(2,	0,	1,	0,	0,	0,	0,	'',	0,	'subshop',	'OXID eShop 6',	'OXID eShop 6',	'',	'',	'online kaufen',	'purchase online',	'',	'',	'Der Onlineshop',	'Online Shop',	'',	'',	'info@myoxideshop.com',	'reply@myoxideshop.com',	'order@myoxideshop.com',	'Ihre Bestellung bei OXID eSales',	'Vielen Dank für Ihre Registrierung im OXID eShop',	'Ihr Passwort im OXID eShop',	'Ihre OXID eSales Bestellung wurde versandt',	'Your order at OXID eShop',	'Thank you for your registration at OXID eShop',	'Your OXID eShop password',	'Your OXID eSales Order has been shipped',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'Your Company Name',	'2425 Maple Street',	'9041',	'Any City, CA',	'United States',	'Bank of America',	'1234567890',	'900 1234567',	'',	'',	'',	'',	'John',	'Doe',	'217-8918712',	'217-8918713',	'www.myoxideshop.com',	'',	'',	'',	'',	'',	'',	'',	'',	'EE',	'6.0.0',	1,	1,	1,	1,	'2018-08-23 13:40:25',	'');

INSERT INTO `oxconfig` (`OXID`, `OXSHOPID`, `OXMODULE`, `OXVARNAME`, `OXVARTYPE`, `OXVARVALUE`) VALUES
('aLanguagesForShop2', 2, '', 'aLanguages', 'aarr', 'a:2:{s:2:"de";s:7:"Deutsch";s:2:"en";s:7:"English";}'),
('aSEOReservedWordsForShop2', 2, '', 'aSEOReservedWords', 'arr', 'a:7:{i:0;s:5:"admin";i:1;s:4:"core";i:2;s:6:"export";i:3;s:7:"modules";i:4;s:3:"out";i:5;s:5:"setup";i:6;s:5:"views";}')
;