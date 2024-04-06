import 'dart:math' as math;
import 'package:flutter/material.dart';
import 'package:nss/components/relative_scale.dart';
import '../utils/app_settings.dart';
import '../utils/style_sheet.dart';
import 'mytext.dart';
class PieChart extends StatefulWidget {

  String title='';
  List values;
  List colors;
  List labels;
  double size=100;
  double stroke=2;
  double textSize=15;
  Color textColor=Colors.black;
  Color backgroudColor=Colors.white;
  double bottomTextSize=0;
  double padding=0;
  bool enableBottomText=true;
  bool centerLabel=false;

   PieChart({Key? key,
     this.title='',
    required this.values,
    required this.colors,
    required this.labels,
    this.size=100,
     this.stroke=2,
     this.textSize=15,
     this.textColor=Colors.black,
     this.backgroudColor=Colors.white,
     this.bottomTextSize=0,
     this.padding=0,
     this.enableBottomText=true,
     this.centerLabel=false,
   }) : super(key: key);

  @override
  State<PieChart> createState() => _PieChartState();
}

class _PieChartState extends State<PieChart> with RelativeScale {

  // String title='';
  // List values=[];
  // List colors=[];
  // List labels=[];
  // double size=100;
  // double stroke=2;
  // double textSize=15;
  // Color textColor=Colors.black;
  // Color backgroudColor=Colors.white;
  // double bottomText=0;
  // double padding=0;



  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);

    return PieChart();


  }

  PieChart() {
    try {
      double total = 0;

      for (var item in widget.values) {
        total = total + item;
      }

      double currentAngle = 0.0;
      List<double> previousAngle = [];
      previousAngle.add(currentAngle);

      for (var item in widget.values) {
        currentAngle = currentAngle + (item / total * 360);
        previousAngle.add(currentAngle);
      }
      return Container(
        width: widget.size,
        padding: EdgeInsets.all(widget.padding),
        child: Column(
          children: [
            Container(
                width: widget.size-(widget.padding*2),
                height: widget.size-(widget.padding*2),
                color: widget.backgroudColor,
                padding: EdgeInsets.all(widget.stroke / 2),
                child: Stack(
                  alignment: Alignment.center,
                  children: [
                    Stack(
                      alignment: Alignment.center,
                      children: [
                        for(int i = 0; i < widget.values.length; i++)
                          GestureDetector(
                            onTap: () {
                              apiTest(i.toString());
                            },
                            child: SemiCircleWidget(
                              diameter: Width(context),
                              sweepAngle: (widget.values[i] / total * 360 >= 360)
                                  ? 359.9
                                  : (widget.values[i] / total * 360),
                              color: widget.colors[i],
                              stroke: widget.stroke,
                              start: previousAngle[i],
                            ),
                          ),
                        Container(
                          width: widget.size - widget.stroke,
                          height: widget.size - widget.stroke,
                          decoration: BoxDecoration(
                              color: Colors.white,
                              shape: BoxShape.circle,
                              boxShadow: [
                                BoxShadow(
                                  color:
                                  Colors.grey.withOpacity(0.3),
                                  spreadRadius: 5,
                                  blurRadius: 7,
                                  offset: Offset(0,
                                      3), // changes position of shadow
                                ),
                              ]),
                        ),
                        Container(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            crossAxisAlignment: CrossAxisAlignment.center,
                            children: [
                              Mytext(widget.title.toString(),  widget.textColor,size:widget.textSize,bold: true,),
                              if(widget.enableBottomText==true && widget.centerLabel==true)bottomtextWidget()

                            ],
                          ),
                        )
                      ],
                    )
                  ],
                )),
            if(widget.enableBottomText==true && widget.centerLabel==false)bottomtextWidget()
          ],
        ),
      );
    } catch (e) {
      return Container(
        width: widget.size,
        height: widget.size,
        child: Mytext('Missing', fc_5),
      );
    }
  }

  bottomtextWidget() {
    return Container(
      padding: EdgeInsets.fromLTRB(0, sy(3), 0, 0),
      width: Width(context),
      alignment: Alignment.center,
      child: Wrap(
        alignment: WrapAlignment.center,
        direction: Axis.horizontal,
        children: [
          for(int i = 0; i < widget.values.length; i++)
            Container(
                padding: EdgeInsets.fromLTRB(
                    sy(5), sy(1), sy(5), sy(2)),
                child: IntrinsicWidth(
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [

                      Container(
                        width: (widget.bottomTextSize == 0)
                            ? sy(widget.textSize / 2)
                            : sy(widget.bottomTextSize / 2),
                        height: (widget.bottomTextSize == 0)
                            ? sy(widget.textSize / 2)
                            : sy(widget.bottomTextSize / 2),
                        color: widget.colors[i],
                      ),
                      SizedBox(width: sy(3),),
                      Mytext(widget.labels[i].toString(), fc_2,
                        size: (widget.bottomTextSize == 0)
                            ? widget.textSize - 2
                            : widget.bottomTextSize,),
                      SizedBox(width: sy(2),),
                      Mytext('' + widget.values[i].toString() + '', fc_2,
                        size: (widget.bottomTextSize == 0)
                            ? widget.textSize - 2
                            : widget.bottomTextSize,),
                    ],
                  ),
                )
            )
        ],
      ),
    );
  }
}

class SemiCircleWidget extends StatelessWidget {
  final double? diameter;
  final double? start;
  final double? sweepAngle;
  final double? stroke;
  final Color? color;

  const SemiCircleWidget({
    Key? key,
    this.diameter = 200,
    this.start = 0,
    @required this.sweepAngle,
    @required this.color,
    this.stroke=50,
  }) : super(key: key);

  @override
  Widget build(BuildContext context){
    return CustomPaint(
      painter: MyPainter(sweepAngle,start,color,stroke),
      size: Size(diameter!, diameter!),
    );
  }
}

class MyPainter extends CustomPainter {
  MyPainter(this.sweepAngle,this.start, this.color,this.stroke);
  final double? sweepAngle;
  final double? start;
  final Color? color;
  final double? stroke;

  @override
  void paint(Canvas canvas, Size size) {
    final Paint paint = Paint()
      ..strokeWidth =stroke!
      ..style = PaintingStyle.stroke
      ..color = color!;

    double degToRad(double deg) => deg * (math.pi / 180.0);

    final path = Path()
      ..arcTo(
          Rect.fromCenter(
            center: Offset(size.height / 2, size.width / 2),
            height: size.height,
            width: size.width,
          ),
          degToRad(start!),
          degToRad(sweepAngle!),
          false);

    canvas.drawPath(path, paint);
  }

  @override
  bool shouldRepaint(CustomPainter oldDelegate) => false;
}


/*PieChart(
title: 'dd',
size: Width(context)/2,
values: [10,10,10,20,20,20],
labels: ['Bus','Van','Car','Bike','GGG','GDS'],
colors: [Colors.pink,Colors.blue,Colors.cyan,Colors.brown,Colors.grey,Colors.orange],
stroke: 60,
textColor: Colors.brown,
textSize: sy(n),
backgroudColor: fc_bg,
bottomText: sy(s-1),
padding: sy(5),

),*/
