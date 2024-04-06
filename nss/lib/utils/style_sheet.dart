import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

//Colors
const Color ThemePrimary = Color(0xFF332C6F);
const Color ThemeSecondary = Color(0xFFEEE7DD);
Color StatusBarColor = Colors.white;

bool isDark=false;
Color fc_bg=Colors.white;
Color fc_bg2 = Colors.grey.shade100;
Color fc_textfield_bg= Color(0xFFF0F4F7);
Color fc_1 = Colors.grey.shade900;
Color fc_2 = Colors.grey.shade800;
Color fc_3 = Colors.grey.shade700;
Color fc_4 = Colors.grey.shade600;
Color fc_5 = Colors.grey.shade400;
Color fc_6 = Colors.grey.shade200;
Color fc_nav_body=Colors.white;
Color fc_icon=Colors.grey.shade800;
Color fc_stock= Colors.grey.shade300;

//FONTS
double xxl = 20;
double xl = 16;
double ll = 14;
double l = 12;
double n = 10;
double s = 8;
double xs = 6;

double Width(BuildContext context) => MediaQuery.of(context).size.width;
double Height(BuildContext context) => MediaQuery.of(context).size.height;

//APP Theam
ThemeData TheamFont = ThemeData(
    primaryColor: ThemePrimary, textTheme: GoogleFonts.ubuntuTextTheme());
//TEXT STYLE
TextStyle ts_Regular(double? size, Color? color) {
  return TextStyle(
    fontSize: size,
    color: color,
    //  height: 1.2,
    fontWeight: FontWeight.w500,
  );
}

TextStyle ts_Regular_spaced(double? size, Color? color, double space) {
  return TextStyle(
      fontSize: size,
      color: color,
      fontWeight: FontWeight.w500,
      height: 1.2,
      letterSpacing: space);
}

TextStyle ts_Bold_Weight(double size, Color? color, FontWeight fontWeight) {
  return TextStyle(
      fontSize: size,
      color: color,
      fontWeight: fontWeight,
      height: 1.5,
      fontStyle: FontStyle.normal);
}

TextStyle ts_Italic(double? size, Color? color) {
  return TextStyle(
      fontSize: size,
      color: color,
      fontWeight: FontWeight.w400,
      height: 1.5,
      fontStyle: FontStyle.italic);
}

TextStyle ts_Bold(double? size, Color? color,
    {FontWeight fontWeight = FontWeight.w700}) {
  return TextStyle(
    color: color,
    fontSize: size,
    fontWeight: fontWeight,
    height: 1.2,
  );
}

TextStyle ts_Bold_space(double? size, Color? color, double space) {
  return TextStyle(
      fontSize: size,
      color: color,
      fontWeight: FontWeight.w700,
      letterSpacing: space);
}

TextStyle ts_regular_strike(double? size, Color? color) {
  return TextStyle(
    fontSize: size,
    color: color,
    decoration: TextDecoration.lineThrough,
  );
}

TextStyle ts_Bold_strike(double? size, Color? color) {
  return TextStyle(
    fontSize: size,
    color: color,
    fontWeight: FontWeight.bold,
    decoration: TextDecoration.lineThrough,
  );
}

TextStyle ts_Regular_underline(double? size, Color? color) {
  return TextStyle(
    fontSize: size,
    color: color,
    decoration: TextDecoration.underline,
  );
}

TextStyle ts_stock(double? size, Color? color, Color? stoke) {
  return TextStyle(
    fontSize: size,
    foreground: Paint()
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1
      ..color = stoke!,
  );
}

BoxDecoration darkBox = BoxDecoration(
  gradient: LinearGradient(
    begin: Alignment.centerLeft,
    end: Alignment.centerRight,
    colors: <Color>[
      Colors.white,
      ThemePrimary.withOpacity(0.2),
    ],
  ),
);

//TextField
UnderlineInputBorder textfieldBorder(Color color) {
  return UnderlineInputBorder(
    borderSide: BorderSide(color: color),
  );
}

//Container
BoxDecoration decoration_image(
  Color? color,

  String imglink,
  double topLeft,
  double topRight,
  double bottomLeft,
  double bottomRight,{
    double opacity=1,
      BoxFit fit=BoxFit.cover,

}

) {
  return BoxDecoration(
    color: color,
    borderRadius: BorderRadius.only(
      topLeft: Radius.circular(topLeft),
      topRight: Radius.circular(topRight),
      bottomLeft: Radius.circular(bottomLeft),
      bottomRight: Radius.circular(bottomRight),
    ),
    image: DecorationImage(
      image: AssetImage(imglink),
      opacity: opacity,
      fit: fit,
    ),
  );
}


BoxDecoration decoration_round(Color? color, double topLeft, double topRight,
    double bottomLeft, double bottomRight) {
  return BoxDecoration(
    color: color,
    borderRadius: BorderRadius.only(
      topLeft: Radius.circular(topLeft),
      topRight: Radius.circular(topRight),
      bottomLeft: Radius.circular(bottomLeft),
      bottomRight: Radius.circular(bottomRight),
    ),
  );
}

BoxDecoration decoration_border(
    Color? color,
    Color? bordercolor,
    double borderWidth,
    double topLeft,
    double topRight,
    double bottomLeft,
    double bottomRight) {
  return BoxDecoration(
    color: color,
    border: Border.all(color: bordercolor!, width: borderWidth),
    borderRadius: BorderRadius.only(
      topLeft: Radius.circular(topLeft),
      topRight: Radius.circular(topRight),
      bottomLeft: Radius.circular(bottomLeft),
      bottomRight: Radius.circular(bottomRight),
    ),
  );
}

ButtonStyle elevatedButton(Color? color, double radius,
    {double elevation = 0}) {
  return ElevatedButton.styleFrom(
    backgroundColor: color,
    elevation: elevation,
    shape: RoundedRectangleBorder(
      borderRadius: BorderRadius.circular(radius),
    ),
  );
}

ButtonStyle elevatedButtonTrans({double padding = 10}) {
  return ElevatedButton.styleFrom(
    backgroundColor: fc_bg,
    elevation: 0,
    padding: EdgeInsets.fromLTRB(padding, padding, padding, padding),
    tapTargetSize: MaterialTapTargetSize.shrinkWrap,
    shape: RoundedRectangleBorder(
      borderRadius: BorderRadius.circular(5),
    ),
  );
}

ButtonStyle elevatedButtonBorder(Color? color, double radius,{double width=1}) {
  return ElevatedButton.styleFrom(
    backgroundColor: Colors.transparent,
    elevation: 0,
    shape: RoundedRectangleBorder(
      side: BorderSide(style: BorderStyle.solid, color: color!, width: width ),
      borderRadius: BorderRadius.circular(radius),
    ),
  );
}
