import 'package:flutter/material.dart';

class TransparantRiseScreen extends PageRouteBuilder {
  final Widget widget;

  TransparantRiseScreen({required this.widget})
      : super(
      opaque: false,
      pageBuilder: (BuildContext context,  _, __) {

        return widget;
      } , transitionsBuilder: (BuildContext context,
      Animation<double> animation,
      Animation<double> secondaryAnimation,
      Widget child) {


    return Container(
      color: Colors.black45,
      child: SlideTransition(
        position: new Tween<Offset>(
          begin: const Offset(0.0, 1.0),
          end: Offset.zero,
        ).animate(animation),
        child: child,
      ),
    );

  }
  );

}