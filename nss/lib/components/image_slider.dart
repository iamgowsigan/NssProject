import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/material.dart';
import 'package:nss/components/custom_image_viewer.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/utils/urls.dart';

import '../router/open_screen.dart';
import '../utils/style_sheet.dart';
import 'imageListFullScreen.dart';

class ImageSlider extends StatefulWidget {
  List imageList=[];
  String imageKey='';
  double height=500;
  ImageSlider({Key? key,required this.imageList, required this.imageKey, required this.height}) : super(key: key);

  @override
  State<ImageSlider> createState() => _ImageSliderState();
}

class _ImageSliderState extends State<ImageSlider> with RelativeScale {
  int curBanner=0;

  @override
  void initState() {
    super.initState();
  }

  @override
  void dispose() {
    super.dispose();
  }


  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return Container(
      width: Width(context),
      height:widget.height,
      child: Stack(
        children: [
          if(widget.imageList.isNotEmpty)Positioned(child: CarouselSlider.builder(
              itemCount: widget.imageList.length,
              options: CarouselOptions(
                  height: widget.height,
                  aspectRatio: 16 / 9,
                  viewportFraction: 1,
                  initialPage: 0,
                  enableInfiniteScroll: true,
                  reverse: false,
                  autoPlay: true,
                  autoPlayInterval: Duration(seconds: 5),
                  autoPlayAnimationDuration: Duration(milliseconds: 800),
                  autoPlayCurve: Curves.linear,
                  enlargeCenterPage: true,
                  scrollDirection: Axis.horizontal,
                  onPageChanged: (val,CarouselPageChangedReason){
                    setState(() {
                      curBanner=val;
                    });
                  }
              ),
              itemBuilder: (BuildContext context, int i,
                  int pageViewIndex) =>
                  GestureDetector(
                    onTap: () {

                      //Navigator.push(context, OpenScreen(widget: ImageFullScreen(imgurl: Urls.imageLocation+imageList[i]['i_image'].toString(),)));
                      Navigator.push(context, OpenScreen(widget: ImageListFullScreen(imageList: widget.imageList,index: i,imageKey: widget.imageKey,)));
                    },
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(sy(0)),
                      child:  CustomeImageView(
                        image: Urls.imageLocation +
                            widget.imageList[i][widget.imageKey].toString(),
                        width: Width(context),
                        height: Height(context),
                        placeholder: Urls.DummyImageBanner,
                        fit: BoxFit.cover,
                        blurBackground: true,
                      ),
                    ),
                  )
          ),
            top: 0,left: 0,right: 0,bottom: 0,),
          if(widget.imageList.isNotEmpty)Align(
              alignment: Alignment.bottomCenter,
              child: Container(
                margin: EdgeInsets.fromLTRB(sy(10), sy(0), sy(10), sy(40)),
                width: Width(context)*0.3,
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    for(int i=0; i<widget.imageList.length;i++)
                      Expanded(child: Container(
                        margin: EdgeInsets.fromLTRB(sy(2), sy(3), sy(2), sy(3)),
                        child:  Container(
                          width: Width(context),height: sy(2),
                          decoration: decoration_round( (curBanner==i)?Colors.white:Colors.white38,sy(10), sy(10), sy(10), sy(10)),
                        ),
                      ))

                  ],
                ),
              )
          ),
         ],
      ),
    );
  }
}
