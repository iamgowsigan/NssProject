import 'package:flutter/material.dart';
import '../library/zoom.dart';
import '../utils/const.dart';
import '../utils/style_sheet.dart';
import '../utils/urls.dart';

class ImageListFullScreen extends StatelessWidget {
  List imageList=[];
  String imageKey;
  int index=0;
  ImageListFullScreen({Key? key,required this.imageList, this.imageKey='',this.index=0}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    // TODO: implement build
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      builder: (context, child) {
        return Directionality(
          textDirection:
          Const.AppLanguage == 0 ? TextDirection.ltr : TextDirection.rtl,
          child: child!,
        );
      },
      home: SafeArea(
        top: false,
        child: Container(
          color: Colors.black.withOpacity(0.3),
          width: Width(context),
          height: Height(context),
          child:  Stack(
            fit: StackFit.expand,
            alignment: Alignment.center,
            children: [
              Positioned(
                  top: 0,
                  bottom: 0,
                  left: 0,
                  right: 0,
                  child: PageView(
                    scrollDirection: Axis.horizontal,
                    controller: PageController(initialPage: index),
                    children: [
                      for(int i=0;i<imageList.length;i++)
                        Zoom(
                            //maxZoomWidth: 1800,
                            //maxZoomHeight: 1800,
                            //  initZoom: 0.0,
                          initTotalZoomOut: true,
                          initScale: 0,
                          centerOnScale: true,

                            backgroundColor: Colors.black.withOpacity(0.5),
                            child:  Container(
                              alignment: Alignment.center,
                              color: fc_bg,
                              child: FadeInImage.assetNetwork(
                                image: Urls.imageLocation+imageList[i][imageKey],
                                placeholder:  Urls.DummyImageBanner,
                                width: Width(context),
                                fit: BoxFit.contain,
                              ),
                            ),

                          ),


                    ],
                  )),


              Positioned(
                top:MediaQuery.of(context).padding.top+15,
                right: 15,
                child: Container(
                    width: 30,
                    height: 30,
                    child: Material(
                        borderRadius: BorderRadius.circular(50),
                        color: fc_bg,
                        child: Container(
                          width: 30,
                          height: 30,
                          alignment: Alignment.center,
                          child: GestureDetector(
                            onTap: (){
                              Navigator.of(context).pop(true);
                            },
                            child: Icon(Icons.clear,size: 20,color: fc_2,),
                          ),
                        )
                    )
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

