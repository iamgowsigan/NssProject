import 'dart:ui';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:nss/utils/style_sheet.dart';
import 'package:nss/utils/urls.dart';

class CustomeImageView extends StatefulWidget {
  String image;
  String placeholder;
  double width;
  double height;
  double radius;
  BoxFit fit;
  AlignmentGeometry alignment;
  bool blurBackground;
  Color imgColor;
  bool isTrans;

  CustomeImageView({Key? key,required this.image, this.width=0, this.height=0, this.placeholder='assets/images/banner.png', this.fit=BoxFit.cover,this.alignment=Alignment.center,this.blurBackground=true,this.radius=0,this.imgColor=Colors.transparent,this.isTrans=false}) : super(key: key);
  @override
  _CustomeImageViewState createState() {
    return _CustomeImageViewState();
  }
}

class _CustomeImageViewState extends State<CustomeImageView> {

  @override
  Widget build(BuildContext context) {
    // TODO: implement build
    return Container(
      child: ClipRRect(
        borderRadius: BorderRadius.circular(widget.radius),
        child: (widget.isTrans==false)?cacheWidget():cacheWidgetTransparent(),
      ),
    );
  }

  cacheWidget() {
    return Container(
      width: widget.width,
      height: widget.height,
      child: Stack(
        children: [
          if(widget.blurBackground==true)Positioned(child: ClipRRect(
            child: ImageFiltered(
              imageFilter: ImageFilter.blur(sigmaX: 5, sigmaY: 5),
              child: Image.network(
                widget.image,
                fit:BoxFit.cover,
                width: widget.width,
                height: widget.height,
                //     color: Colors.black,
                //   colorBlendMode: BlendMode.softLight,
              ),
            ),
          ),top: 0,bottom: 0,left: 0,right: 0,),
          Positioned(child: CachedNetworkImage(
            imageUrl:  widget.image,
            width: widget.width,
            height: widget.height,
            color:(widget.imgColor==Colors.transparent)?null:widget.imgColor,
            fit: widget.fit,

            placeholder: (context, url) => Image.asset(Urls.DummyImageBanner,width: widget.width,height: widget.height,fit: BoxFit.cover,),
            errorWidget: (context, url, error) => Image.asset(Urls.DummyImageBanner,width: widget.width,height: widget.height,fit: BoxFit.cover,),
          ),top: 0,bottom: 0,left: 0,right: 0,),

        ],
      ),
    );
  }

  cacheWidgetTransparent() {
    return Container(
      width: widget.width,
      height: widget.height,

      child: Stack(
        children: [

          Positioned(child: CachedNetworkImage(
            imageUrl:  widget.image,
            width: widget.width,
            height: widget.height,
            color:(widget.imgColor==Colors.transparent)?null:widget.imgColor,
            fit: widget.fit,

        //    placeholder: (context, url) => Image.asset(Urls.DummyImageBanner,width: widget.width,height: widget.height,fit: BoxFit.cover,),
       //     errorWidget: (context, url, error) => Image.asset(Urls.DummyImageBanner,width: widget.width,height: widget.height,fit: BoxFit.cover,),
          ),top: 0,bottom: 0,left: 0,right: 0,),

        ],
      ),
    );
  }

  fadeWidget(){
    return  FadeInImage.assetNetwork(
      image: widget.image,
      width: widget.width,
      height: widget.height,
      placeholder:  Urls.DummyImageBanner,
      fit: widget.fit,
    );
  }
  progressWidget() {
    return Container(
      width: widget.width,
      height: widget.height,
      child: Stack(
        children: [
          if(widget.blurBackground==true)Positioned(child: ClipRRect(
            child: ImageFiltered(
              imageFilter: ImageFilter.blur(sigmaX: 5, sigmaY: 5),
              child: Image.network(
                widget.image,
                fit:BoxFit.cover,
                width: widget.width,
                height: widget.height,
           //     color: Colors.black,
             //   colorBlendMode: BlendMode.softLight,
              ),
            ),
          ),top: 0,bottom: 0,left: 0,right: 0,),
          Positioned(child: Image.network(
            widget.image,
            fit:widget.fit,
            width: widget.width,
            height: widget.height,
            loadingBuilder:(BuildContext context, Widget child,ImageChunkEvent ?loadingProgress) {
              if (loadingProgress == null) return child;
              return Center(
                child: Container(
                  color: Colors.transparent,
                  width: widget.width,
                  height: widget.height,
                  child:  Column(
                    crossAxisAlignment: CrossAxisAlignment.center,
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      SizedBox(
                        height: 25,
                        width: 25,
                        child: CircularProgressIndicator(
                          valueColor:AlwaysStoppedAnimation<Color>(ThemePrimary),
                          strokeWidth: 2,
                          value: loadingProgress.expectedTotalBytes != null ?
                          loadingProgress.cumulativeBytesLoaded / int.parse((loadingProgress.expectedTotalBytes).toString())
                              : null,
                        ),
                      )
                    ],
                  ),
                ),
              );
            },
          ),top: 0,bottom: 0,left: 0,right: 0,),

        ],
      ),
    );
  }
}