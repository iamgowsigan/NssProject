import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:nss/utils/const.dart';
import 'package:nss/utils/style_sheet.dart';

String Lang(String? Eng, String? Arab) {
  if (Const.AppLanguage == 1) {
    return Arab!;
  } else {
    return Eng!;
  }
}

String cur_Lang = "";
String cur_Lang_code = "";
String contry_code = "971";
String home_country = "971";
int splashScreen = 0;

String screenName = "";
String screenID = "";

apiTest(String data) {
  debugPrint('API RESULT');
  debugPrint(data);
}


class AppSetting with ChangeNotifier {
  ThemeMode appTheam = ThemeMode.light;
  String sid = '0';
  String name = '';
  String lastname = '';
  String phone = '';
  String exp = '';
  String email = '';
  String emailVerify = '0';
  String profile = '';
  String country = '';
  String city = '';
  String map = '';
  String about = '';
  String gender = '';
  String fbtoken = '';
  String skipSignup = '0';
  String isVendor = '0';
  String dob = '';
  String reg_no = '';
  String expert = '';
  String subscribevalid = '';
  String subscribe = '';

  String shopname = '';
  String shopcategory = '';
  String shopmap = '';
  String shoplive = '';
  String shoplandline = '';
  String shopaddress = '';
  String bankname = '';
  String bankholder = '';
  String bankac = '';
  String bankcode = '';


  String devicemap = '';
  String devicelat = '';
  String devicelng = '';



  bool darkSelected = false;

  void changeTheme(ThemeMode getappTheam) {
    if (getappTheam == ThemeMode.light) {
      appTheam = getappTheam;
      darkSelected = true;
      isDark = false;
      fc_bg = Color(0xFF23262D);
      fc_bg2 = Color(0xFF292B38);
      fc_nav_body = Color(0xFF1c1c1c);
      fc_textfield_bg = Color(0xFF343547);
      fc_icon = Color(0xFF36578D);
      fc_stock = Color(0xFF41455C);
      fc_1 = Colors.white;
      fc_2 = Colors.grey.shade100;
      fc_3 = Colors.grey.shade200;
      fc_4 = Colors.grey.shade300;
      fc_5 = Colors.grey.shade500;
      fc_6 = Colors.grey.shade700;
    } else {
        appTheam = getappTheam;
        isDark = true;
        darkSelected = false;
        fc_bg = Colors.white;
        fc_bg2 = Colors.grey.shade50;
        fc_nav_body = Colors.white;
        fc_icon = Color(0xFF27949C);
        fc_textfield_bg = Color(0xFFF0F4F7);
        fc_stock = Colors.grey.shade300;
        fc_1 = Colors.grey.shade900;
        fc_2 = Colors.grey.shade800;
        fc_3 = Colors.grey.shade700;
        fc_4 = Colors.grey.shade600;
        fc_5 = Colors.grey.shade400;
        fc_6 = Colors.grey.shade200;

    }
    notifyListeners();
  }


}

class MyThemes {

  static final darkTheme = ThemeData(
    scaffoldBackgroundColor: Colors.grey.shade900,
    primaryColor: Colors.black,
    colorScheme: ColorScheme.dark(),
    textTheme: GoogleFonts.ubuntuTextTheme(),
    cardColor: Colors.grey.shade900,
    primaryColorDark: Colors.brown,
    dividerColor: Colors.grey.shade200,
    backgroundColor: Colors.grey.shade900,
    elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
      backgroundColor: ThemePrimary,
    )),
  );

  static final lightTheme = ThemeData(
    scaffoldBackgroundColor: Colors.white,
    primaryColor: Colors.white,
    colorScheme: ColorScheme.light(),
    primaryColorDark: Colors.red,
    textTheme: GoogleFonts.ubuntuTextTheme(),
    cardColor: Colors.white,
    dividerColor: Colors.white,
    backgroundColor: Colors.white,
    elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: ThemePrimary,
    )),
  );
}

