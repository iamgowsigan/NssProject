import 'package:flutter/material.dart';

class Const {
  static ThemeMode _themeMode = ThemeMode.light;
  static String AppName = "National Service Scheme";
  static String APPCODE = "NSS";
  static String AppDescription = "Attendance Monitoring Application";
  static String APPKEY = "2022@mec*app";
  static String SPLASHSCREENIMAGE = "SPLASHSCREENIMAGE";
  static String SPLASHSCREENTIME = "SPLASHSCREENTIME";

  static String POSTHEADER = '{"Content-type": "application/json","Access-Control-Allow-Origin":"*","Access-Control-Allow-Methods":"application/json"}';

  static var APPVERSION = "APPVERSION";
  static int APP_CURRENT_VER = 1;
  static int APP_SERVER_VER = 1;
  static String APP_IOS_URL = '';
  static String APP_ANDROID_URL = '';
  static String WHATSAPP_TOKEN = "";
  static String TRANSLATION_TOKEN = "";
  static String SMSGATEWAY = "";
  static String FIXER_API = "";
  static String GOOGLE_MAP_PLACE = "";
  static String OTPEMAIL = "";
  static String OTPEMAIL_PASSWORD = "";
  static String WHATSAPPNUMBER = "";
  static String SHAREEMAIL = "";
  static String Website = "";
  static String ShareLink = "";
  static String OTP = "";

  static String VENDORNOTIFICATION = "";
  static String LOCALDELIVERYCOST = "";
  static String LOCALDELIVERYTIME = "";
  static String LOCALREMOTEDELIVERYCOST = "";
  static String LOCALREMOTEDELIVERYTIME = "";
  static String INTERNATIONALDELIVERYCOST = "";
  static String INTERNATIONALDELIVERYTIME = "";
  static String ENABLECOD = "";
  static String ENABLEONLINE = "";


  static String FBTOKEN = "FBTOKEN";
  static String LINK_ID = "LINK_ID";
  static String LINK_PAGE = "LINK_PAGE";
  static int AppLanguage = 0; //0-English  1- Arabic

  static String SKIP_SIGNUP_VALUE = "0"; //0-English  1
  static String DARKMODE = "0";
  static String TAX = "0";
  static String SKIP_SIGNUP = "SKIP_SIGNUP";
  static String ISVENDOR = "ISVENDOR";
  static int cartSize = 0;

  //User
  static String SID = "SID";
  static String NAME = "NAME";
  static String LASTNAME = "LASTNAME";
  static String PHONE = "PHONE";
  static String PROFILE = "PROFILE_PICTURE";
  static String COUNTRY = "COUNTRY";
  static String CITY = "CITY";
  static String EMAIL = "EMAIL";
  static String EMAIL_VERIFY = "EMAIL_VERIFY";
  static String ADDRESS = "ADDRESS";
  static String GENDER = "GENDER";
  static String DOB = "DOB";
  static String ABOUT = "ABOUT";
  static String EXPERT = "EXPERT";
  static String EXP = "EXP";
  static String REGNO = "REGNO";
  static String SUBSCRIBEVALID = "SUBSCRIBEVALID";
  static String SUBSCRIBE = "SUBSCRIBE";
  static String SHOPNAME = "SHOPNAME";
  static String SHOPCATEGORY = "SHOPCATEGORY";
  static String SHOPMAP = "SHOPMAP";
  static String SHOPLIVE = "SHOPLIVE";
  static String SHOPLANDLINE = "SHOPLANDLINE";
  static String SHOPADDRESS = "SHOPADDRESS";
  static String BANKNAME = "BANKNAME";
  static String BANKHOLDER = "BANKHOLDER";
  static String BANKAC = "BANKAC";
  static String BANKCODE = "BANKCODE";

  //Social


  //Delivery
  static String DEFAULT_ADDRESS_ID = "DEFAULT_ADDRESS_ID";
  static String DEFAULT_ADDRESS = "DEFAULT_ADDRESS";
  static String DEFAULT_CITY = "DEFAULT_CITY";
  static String DEFAULT_COUNTRY = "DEFAULT_COUNTRY";




  //App

  static String CURRENCY = " AED";
  static String WELCOMESCREEN = "welcomescreen";
  static String SETAPPLANGUAGE = "setapplanguage";
  static String SELECTEDLANGUGAE = "SELECTEDLANGUGAE";
  static String DB_LANG = "DB_LANG";
  static String BOTTOM_MENU = "BOTTOM_MENU";
  static String AD_VERSION = "AD_VERSION";

  // static var CURRENCY="AED";
  static var DEFAULT_CURRENCY_LAB = "AED";
  static var SELECTED_CURRENCY = "SELECTED_CURRENCY";
  static var CURRENCY_LAB = "AED";
  static double CURRENCY_VALUE = 1;

  static double CUR_AED = 0;
  static double CUR_BHD = 0;
  static double CUR_KWD = 0;
  static double CUR_EGP = 0;
  static double CUR_OMR = 0;
  static double CUR_QAR = 0;
  static double CUR_SAR = 0;
  static double CUR_INR = 0;

  static String CUR_AED_LAB = "AED";
  static String CUR_BHD_LAB = "BHD";
  static String CUR_KWD_LAB = "KWD";
  static String CUR_EGP_LAB = "EGP";
  static String CUR_OMR_LAB = "OMR";
  static String CUR_QAR_LAB = "QAR";
  static String CUR_SAR_LAB = "SAR";
  static String CUR_INR_LAB = "INR";



  static List VENDORCATEGORYLIST = [];
  static List CATEGORYLIST = [];
  static List SUBCATEGORYLIST = [];
  static List FILTERLIST = [];
  static List LABLELIST = [];
  static List PLANLIST = [];


  static List LightColor = [
    Color(0xFF57E78F),
    Color(0xFFF4BA86),
    Color(0xFFFF958C),
    Color(0xFFEFA3EA),
    Color(0xFF56ABAB),
    Color(0xFF8CA7F3),
    Color(0xFF9E99FF),
    Color(0xFFFAE071),
    Color(0xFF8875B5),
    Color(0xFF57DCE7),
    Color(0xFFFC7E7E),
    Color(0xFFF6D5D2),
  ];



  static List AvailableLanguages = [
    {"id": 0, "label": "English","langstring": "", "flag":"assets/images/flag/gb.png"},
    {"id": 1, "label": "عربى","langstring": "_arab", "flag":"assets/images/flag/ae.png"}
  ];

  static List AvailableCurrency = [
    {"id": "AED", "label": "United Arab Emirates Dirham", "label_arab":"درهم الامارات العربية المتحدة", "flag":"assets/images/flag/ae.png"},
    {"id": "KWD", "label": "Kuwaiti Dinar", "label_arab":"دينار كويتي", "flag":"assets/images/flag/kw.png"},
    {"id": "OMR", "label": "Omani Rial", "label_arab":"ريال عماني", "flag":"assets/images/flag/om.png"},
    {"id": "QAR", "label": "Qatari Riyal", "label_arab":"ريال قطري", "flag":"assets/images/flag/qa.png"},
    {"id": "SAR", "label": "Saudi Arabian Riyal", "label_arab":"ريال سعودي", "flag":"assets/images/flag/sa.png"},
    {"id": "BHD", "label": "Bahraini Dinar", "label_arab":"دينار بحريني", "flag":"assets/images/flag/bh.png"},
    {"id": "EGP", "label": "Egyptian Pound", "label_arab":"الجنيه المصري", "flag":"assets/images/flag/eg.png"},
    {"id": "USD", "label": "United States Dollar", "label_arab":"دولار الولايات المتحدة", "flag":"assets/images/flag/us.png"},
    {"id": "INR", "label": "Indian Rupee", "label_arab":"روبية هندية", "flag":"assets/images/flag/in.png"}
  ];

  static List gender = [
    {"id": "male", "label": "Male", "label_arab":"ذكر"},
    {"id": "female", "label": "Female", "label_arab":"أنثى"}
  ];


  static List expertLevel = [
    {"id": "1", "label": "Student", "label_arab":"Student"},
    {"id": "2", "label": "Graduate", "label_arab":"Graduate"},
    {"id": "3", "label": "Doctorate", "label_arab":"Doctorate"},
    {"id": "4", "label": "Research Scholar", "label_arab":"Research Scholar"}
  ];


  static List sortByList = [
    {"id": "1", "label": "By Publication Date", "label_arab":"By Publication Date"},
    {"id": "2", "label": "By Latest Post", "label_arab":"By Latest Post"},
    {"id": "3", "label": "By Most Viewed", "label_arab":"By Most Viewed"},
    {"id": "4", "label": "By Top Rated", "label_arab":"By Top Rated"},
  ];


  static List unitArray = [
    {"id":"1" , "label":"piece" , "label_arab":"قطعة" },
    {"id":"2" , "label":"gram" , "label_arab":"غرام" },
    {"id":"3" , "label":"ml" , "label_arab":"مل" },
    {"id":"4" , "label":"liter" , "label_arab":"لتر" },
    {"id":"5" , "label":"mm" , "label_arab":"مم" },
    {"id":"6" , "label":"ft" , "label_arab":"قدم" },
    {"id":"7" , "label":"inch" , "label_arab":"بوصة" },
    {"id":"8" , "label":"meter" , "label_arab":"متر" },
    {"id":"9" , "label":"sq.ft" , "label_arab":"قدم مربع" },
    {"id":"10" , "label":"sq.meter" , "label_arab":"متر مربع" },
    {"id":"11" , "label":"set" , "label_arab":"تعيين" },
    {"id":"12" , "label":"packet" , "label_arab":"رزمة" },
    {"id":"13" , "label":"box" , "label_arab":"علبة" },
    {"id":"14" , "label":"pair" , "label_arab":"زوج" },
    {"id":"15" , "label":"capsule" , "label_arab":"كبسولة" }
  ];


  //SETTINGS
  static String SET_GENERAL_NOTIFICATION = "SET_GENERAL_NOTIFICATION";
  static String SET_NEW_CONTENT_NOTIFICATION = "SET_NEW_CONTENT_NOTIFICATION";

  static bool generalNotification=true;
  static bool newNotification=true;


}
