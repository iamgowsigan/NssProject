import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/material.dart';
import 'package:nss/components/custom_image_viewer.dart';
import 'package:nss/components/mytext.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/utils/style_sheet.dart';
import 'package:nss/utils/urls.dart';
import 'package:shimmer/shimmer.dart';

import '../../components/countdown_timer.dart';
import '../../components/distance_calc.dart';

class cardBasic extends StatefulWidget {

  String title;
  String image;
  String id;
  String value;
  String subtitle;
  double width;
  double height;
  BoxFit fit=BoxFit.cover;
  cardBasic(
      {Key? key,
        this.title='',this.image='',  this.id='', this.value='',  this.subtitle='',this.width=30, this.height=25, this.fit=BoxFit.cover

      })
      : super(key: key);

  @override
  State<cardBasic> createState() => _cardBasicState();
}

class _cardBasicState extends State<cardBasic> with RelativeScale {
  @override
  void initState() {

    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return Container(
      child: Container(
        child: Column(
          children: [
            Container(
              child: Row(
                children: [
                  if(widget.image!='')CustomeImageView(image: Urls.imageLocation+widget.image,width: sy(widget.width),height: sy(widget.height),blurBackground: false,fit:widget.fit,radius: sy(3),),
                  Expanded(child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Mytext(widget.title,fc_2,padding: sy(5),size: sy(n-1),bold: (widget.id==widget.value && widget.value!='')?true:false,),
                      if(widget.subtitle!='')Mytext(widget.title,fc_3,padding: sy(5),size: sy(s),topSpace: sy(1),),
                    ],
                  )),
                  if(widget.id==widget.value && widget.value!='')Icon(Icons.check_circle,color: Colors.green.shade500,size: sy(l+1),)
                ],
              ),
              padding: EdgeInsets.fromLTRB(sy(5), sy(8), sy(5), sy(8)),
            ),
            Container(width: Width(context),height: 0.5,color: fc_bg2,)
          ],
        ),
      ),
      // padding: EdgeInsets.fromLTRB(sy(5), sy(5), sy(5), sy(5)),
    );
  }

 }
