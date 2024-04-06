import 'package:flutter/material.dart';

class TransparantScreen extends PageRouteBuilder {
  final Widget widget;

  TransparantScreen({required this.widget})
      : super(
      opaque: false,
      pageBuilder: (BuildContext context,  _, __) {

    return widget;
  } , transitionsBuilder: (BuildContext context,
      Animation<double> animation,
      Animation<double> secondaryAnimation,
      Widget child) {


    return FadeTransition(opacity:animation, child: SlideTransition(
      position: new Tween<Offset>(
        begin: const Offset(0.0, 1.0),
        end: Offset.zero,
      ).animate(animation),
      child: child,
    ),alwaysIncludeSemantics: true,);

  }
  );

}