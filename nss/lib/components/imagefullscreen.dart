import 'package:flutter/material.dart';


import '../library/zoom.dart';
import '../utils/const.dart';
import '../utils/style_sheet.dart';
import '../utils/urls.dart';

class ImageFullScreen extends StatelessWidget {
  final String imgurl;
  ImageFullScreen({Key? key,@required this.imgurl=''}) : super(key: key);

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
      home:  SafeArea(
        top: false,
        child: Container(
          color: Colors.black.withOpacity(0.6),
          width: Width(context),
          height: Height(context),
          child:  Stack(
            children: [
               Positioned(
                  top: 0,
                  bottom: 0,
                  left: 0,
                  right: 0,
                  child: Zoom(
                    //maxZoomWidth: 1800,
                    //maxZoomHeight: 1800,
                    //  initZoom: 0.0,
                    initTotalZoomOut: true,
                    initScale: 0,
                    centerOnScale: true,
                    backgroundColor: Colors.black.withOpacity(0.5),
                    child:  Container(

                      color: Colors.white,
                      child: FadeInImage.assetNetwork(
                        image: imgurl,
                        placeholder:  Urls.DummyImageBanner,
                        width: MediaQuery.of(context).size.width,
                        fit: BoxFit.contain,
                      ),
                    ),

                  ),),

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
