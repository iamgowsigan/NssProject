import 'package:flutter/material.dart';
import 'package:nss/screens/dashboard.dart';
import 'package:nss/screens/phone_number.dart';
import 'package:nss/screens/splash_screen.dart';
import 'package:nss/utils/app_settings.dart';
import 'package:nss/utils/style_sheet.dart';
import 'package:provider/provider.dart';

void main() {
  runApp(
    ChangeNotifierProvider(
      create: (_) => AppSetting(),
      child: MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});
  @override
  Widget build(BuildContext context) {
    return  GestureDetector(
      onTap: () {
        //   FocusScopeNode currentFocus = FocusScope.of(context);
        FocusManager.instance.primaryFocus?.unfocus();
        // if (!currentFocus.hasPrimaryFocus) {
        //   currentFocus.unfocus();
        //}
      },
      child:MaterialApp(
        // theme: MyThemes.lightTheme,
        // darkTheme: MyThemes.darkTheme,
          themeMode: isDark ? ThemeMode.dark : ThemeMode.light,
          theme: MyThemes.lightTheme,
          darkTheme: MyThemes.darkTheme,
          builder: (context, child) {
            return Directionality(
              textDirection: TextDirection.ltr,
              child: child!,
            );
          },
          title: 'NSS',
          debugShowCheckedModeBanner: false,
          home: SplashScreen() ),
    );

  }
}


