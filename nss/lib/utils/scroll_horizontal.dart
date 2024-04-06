import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:flutter/scheduler.dart';
import 'package:nss/utils/app_settings.dart';

class ScrollHorizontal extends StatefulWidget {
  final Widget child;
  final ScrollController controller;
  final Duration duration;
  final double afterPixel;
  bool revese=false;
  ScrollHorizontal({Key? key,required this.revese,required this.child,required this.controller, this.duration=const Duration(milliseconds: 200),this.afterPixel=0}) : super(key: key);

  @override
  State<ScrollHorizontal> createState() => _ScrollHorizontalState();
}

class _ScrollHorizontalState extends State<ScrollHorizontal> {
  bool isVisible=true;
  Size mySize = Size.zero;

  @override
  void initState() {
    // TODO: implement initState
    if(widget.afterPixel!=0){
      hide();
    }
    widget.controller.addListener(listen);

    super.initState();


  }

  @override
  void dispose() {
    // TODO: implement dispose
    widget.controller.removeListener(listen);
    super.dispose();

  }
  void listen() {
    final direction= widget.controller.position.userScrollDirection;
    if(direction == ScrollDirection.forward && widget.controller.position.pixels>widget.afterPixel){
      if(widget.revese==false){
        show();
      }else{
        hide();
      }

    }else if(direction==ScrollDirection.reverse){
      if(widget.revese==false){
        hide();
      }else{
        show();
      }
    }


  }



  void show(){
    if(!isVisible)setState(() =>
    isVisible=true
    );
  }

  void hide(){
    if(isVisible)setState(() =>
    isVisible=false);
  }



  @override
  Widget build(BuildContext context) {
    return AnimatedContainer(duration: widget.duration,
      width:isVisible? 50:0,
      child: Wrap(
        children: [
          WidgetSize(onChange: (size){
            setState(() {
              mySize=size;
            });
            apiTest("mySize:" + mySize.toString());
          }, child: widget.child)
        ],
      ),);
  }

  void getSize() {

    Widget containerSIze= WidgetSize(onChange: (size){
      setState(() {
        mySize=size;
      });
      apiTest("mySize:" + mySize.toString());
    }, child: widget.child);
  }


}



class WidgetSize extends StatefulWidget {
  final Widget child;
  final Function onChange;

  const WidgetSize({
    Key? key,
    required this.onChange,
    required this.child,
  }) : super(key: key);

  @override
  _WidgetSizeState createState() => _WidgetSizeState();
}
class _WidgetSizeState extends State<WidgetSize> {
  @override
  Widget build(BuildContext context) {
    SchedulerBinding.instance.addPostFrameCallback(postFrameCallback);
    return Container(
      key: widgetKey,
      child: widget.child,
    );
  }

  var widgetKey = GlobalKey();
  var oldSize;

  void postFrameCallback(_) {
    var context = widgetKey.currentContext;
    if (context == null) return;

    var newSize = context.size;
    if (oldSize == newSize) return;

    oldSize = newSize;
    widget.onChange(newSize);
  }
}