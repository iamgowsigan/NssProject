import 'dart:async';
import 'dart:ui';

import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:nss/components/custom_image_viewer.dart';
import 'package:nss/utils/style_sheet.dart';
import 'package:nss/utils/urls.dart';

class WidgetHelp{

  static getNameFromId(String id,List arr,String key,String value){
    String res='  - -  ';
    if(id!='null' && id!='NULL' && id!=null){
      for(int i=0;i<arr.length;i++)
        if(id.toString()==arr[i][key]){
          res= arr[i][value].toString();
        }
    }
    return res;
 //   return id;
  }

  static getArrayNames(List arr,{int substring=0}){

    String listLable='';
    if(arr.isNotEmpty){
      for(int i=0;i<arr.length;i++){
        if(substring==0){
          listLable=listLable+arr[i].toString()+', ';
        }else{
          listLable=listLable+arr[i].toString().substring(0,substring)+', ';
        }

      }
      return listLable.toString().substring(0,listLable.length-2);
    }else{
      return listLable;
    }



    //   return id;
  }

  static ContainerColor({required Widget child,required Color bgcolor,double verticle=5,double horizontal=5,}){

    return  Container(
      decoration: decoration_round(bgcolor, 5, 5, 5, 5),
      padding: EdgeInsets.fromLTRB(horizontal, verticle, horizontal, verticle),
      child :child,

    );
    //   return id;
  }
  static Widget profilePic(String image,String name,double width,double height,double radius){
    return  Container(
      height:height,
      width:width,
      alignment: Alignment.center,
      child: ClipRRect(
          borderRadius: BorderRadius.circular(radius),
          child: (image!='' && image!='null')?CustomeImageView(
            image: Urls.imageLocation+image.toString(),
            placeholder: Urls.DummyImageBanner,
            fit: BoxFit.cover,
            height: height,
            width:width,
          ):Container(
            height:height,
            width:width,
            color: ThemePrimary,
            alignment: Alignment.center,
            child: Text(name.toString().toUpperCase().substring(0,2),style: ts_Bold(width/3, Colors.white),),
          )),
    );
  }

  static UnderlineInputBorder borderTextField(Color color){
    return UnderlineInputBorder(
      borderSide: BorderSide(color: color),
    );
  }

  static Widget profilePicPlain(String image, double width,double height,double radius){
    return  Container(
      height:height,
      width:width,
      alignment: Alignment.center,
      child: ClipRRect(
          borderRadius: BorderRadius.circular(radius),
          child: (image.toString()!='' && image.toString()!='null')?CustomeImageView(
            image: Urls.imageLocation+image.toString(),
            placeholder: Urls.DummyImageBanner,
            fit: BoxFit.cover,
            height: height,
            width:width,
          ):ClipRRect(
            child: Image.asset('assets/images/blank.png',width: width,height:height,fit: BoxFit.cover,),
            borderRadius: BorderRadius.circular(radius),
          )

      ),
    );
  }


  static String greeting(){

    var hour = DateTime.now().hour;
    if (hour < 12) {
      return 'Good Morning';
    }
    if (hour < 17) {
      return 'Good Afternoon';
    }
    return 'Good Evening';

  }

  static String getDay(){

    var date = DateTime.now();
    return DateFormat('EEEE').format(date);
  }

  static Widget divLine(BuildContext context,{double height=0,double margin=0, }) {
    return Container(width: Width(context),height: height,color: fc_stock,margin: EdgeInsets.fromLTRB(0,margin, 0, margin),);
  }

  static String capitalization(String message){

    String result='';
    if(message!=''){
      result = "${message[0].toUpperCase()}${message.substring(1).toLowerCase()}";
    }
    return result;

  }

  static Widget dashLine(BuildContext context,Color color,{double height=1,double margin=0, int count=70}) {
    return Container(width: Width(context),
    child: Row(
      children: List.generate(count, (index) => Expanded(
        child: Container(
          margin: EdgeInsets.fromLTRB(0, margin, 0, margin),
          color: index%2==0?Colors.transparent
              :color,
          height: height,
        ),
      )),
    ),);
  }

  static circleProgress({double size=0,Color color=Colors.white,double padding=0,}){

    return  Container(
      width: size,
      height: size,
        alignment: Alignment.center,
      padding: EdgeInsets.all(padding),
      child :Align(
        alignment: Alignment.center,
        child: SizedBox(
          width: size,
          height: size,
          child: CircularProgressIndicator(color: color,strokeWidth: 3,
              valueColor: AlwaysStoppedAnimation<Color>(color)),
        ),
      )

    );
    //   return id;
  }

  static String letterCount(String value, int decimal){

     return value.toString().padLeft(decimal,'0');
  }




}
IsNull(value) {

  if(value==null || value=='' || value=='null'){
    return true;
  }else{
    return false;
  }

}
IsEmpty(value) {

  if(value==null || value=='' || value=='0'){
    return true;
  }else{
    return false;
  }

}

IsRegisteredUser(value) {

  if(value!=null && value!='' && value!='0'){
    return true;
  }else{
    return false;
  }

}
emptyWidget(BuildContext context,String message) {
  return Container(
    width: Width(context),
    child: Column(
      children: [
        SizedBox(height: 40,),
        Image.asset('assets/images/emptyimg.png', width: Width(context) * 0.5,
        ),
        //SizedBox(height: 3,),
        Text(message, style: ts_Regular(15, fc_3),),
        SizedBox(height:12,),
      ],
    ),
  );
}
BackgroudSlides(BuildContext context,double opacity,{double blur=10}) {
  int slide=0;


  return Container(
    //  padding: EdgeInsets.fromLTRB(sy(10), sy(10), sy(10), sy(10)),

      width: Width(context),
      height: Height(context),
      color: fc_bg,
      child: Stack(
        children: [
          Positioned(child: Container(
              child: Image.asset(  'assets/images/slide/s${slide+1}.webp',
                width: Width(context),
                height: Height(context),
                fit: BoxFit.cover,)
          ),top:0 ,bottom: 0,left: 0,right: 0,),
          Positioned(child: ClipRRect(
            child: CarouselSlider.builder(
                itemCount:10,
                options: CarouselOptions(
                  height: Height(context),
                  // aspectRatio: 16/9,
                  viewportFraction: 1,
                  initialPage: 0,
                  enableInfiniteScroll: true,
                  reverse: false,
                  pageSnapping: true,
                  autoPlay: true,
                  autoPlayInterval: Duration(seconds: 5),
                  autoPlayAnimationDuration: Duration(milliseconds: 500),
                  autoPlayCurve: Curves.ease,
                  enlargeCenterPage: true,
                  scrollDirection: Axis.horizontal,
                  onPageChanged: (val, CarouselPageChangedReason){
                    slide=val;
                   }
                ),
                itemBuilder: (context, int i, int pageViewIndex) =>
                    Container(
                        child: Image.asset(  'assets/images/slide/s${i+1}.webp',
                          width: Width(context),
                          height: Height(context),
                          fit: BoxFit.cover,)
                    )
            ),
          ),top:0 ,bottom: 0,left: 0,right: 0,),
           Positioned(child:  Center(
            child:  ClipRect(
              child:  BackdropFilter(
                filter:  ImageFilter.blur(sigmaX: blur, sigmaY: blur),
                child:  Container(
                  width: Width(context),
                  height: Height(context),
                  decoration:  BoxDecoration(
                      color: Colors.white.withOpacity(opacity)
                  ),
                ),
              ),
            ),
          ),top: 0,bottom: 0,left: 0,right: 0)
        ],
      )
  );
}
DiveiderWidget(BuildContext context,{double height=1,double padding=3,Color color=Colors.transparent}){
  return Container(
    width: Width(context),
    height: height,
    color: (color==Colors.transparent)?fc_stock:color,
    margin: EdgeInsets.fromLTRB(0, padding-1, 0, padding+2),
  );
}
