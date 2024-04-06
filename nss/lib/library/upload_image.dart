import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_cropper/image_cropper.dart';
import 'package:image_picker/image_picker.dart';
import 'package:nss/components/mytext.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/library/list_animation.dart';
import 'package:nss/utils/app_settings.dart';
import 'package:nss/utils/const.dart';
import 'package:nss/utils/style_sheet.dart';
import 'package:provider/provider.dart';
import 'dart:convert';
import 'package:path/path.dart' as path;
import 'package:http/http.dart' as http;


class UploadImage extends StatefulWidget{
  String url='';
  List keys=[];
  List values=[];
  bool showallimage=true;
  UploadImage({Key? key, this.url='',required this.keys,required this.values,this.showallimage=true }) : super(key: key);

  @override
  State<UploadImage> createState() => _UploadImageState();
}

class _UploadImageState extends State<UploadImage>  with RelativeScale {


  late File finalImage;
  Map data = Map();
  String result='';
  String murl='';
  List mkey=[];
  List mvalue=[];
  bool isDefault=false;


  @override
  void initState() {
    murl=widget.url;
    mkey=widget.keys;
    mvalue=widget.values;

    super.initState();


  }

  @override
  void dispose() {
    super.dispose();
  }

  void updateToServer() async {
    print(mvalue[0]);
    print(murl);
    var request = http.MultipartRequest("POST", Uri.parse(murl));
    for(int i=0;i<mkey.length;i++){
      request.fields[mkey[i]] = mvalue[i];
    }
    if(isDefault==true){
      request.fields["defaultimage"] =result;
    }else{
      var stream  = http.ByteStream(finalImage.openRead())..cast();
      var length = await finalImage.length();
      var multipartFile = http.MultipartFile('image', stream, length, filename: path.basename(finalImage.path));
      request.files.add(multipartFile);
    }
    request.fields["key"] = Const.APPKEY;



    await request.send().then((response) async {
      response.stream.transform(utf8.decoder).listen((value) {
        data = json.decode(value);
        if (data["success"] == true) {
          apiTest(data["sql"].toString());
          result=data["filename"].toString();
          closeScreen();
        }
        //  print(value);
      });
    }).catchError((e) {
      print(e);
    });
  }

  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return MaterialApp(
        themeMode: Provider.of<AppSetting>(context).appTheam,
        debugShowCheckedModeBanner: false,
        builder: (context, child) {
          return Directionality(
            textDirection:
            Const.AppLanguage == 0 ? TextDirection.ltr : TextDirection.rtl,
            child: child!,
          );
        },
      //color: Colors.grey.withOpacity(0.8),
      home: SafeArea(
        child: GestureDetector(
          onTap: (){
            closeScreen();
          },
          child:screenBody(),
        ),
      )
    );
  }

  screenBody() {
    double itemSize=Width(context)*0.165;
    return   Container(
        width: Width(context),
        height: Height(context),
        color: Colors.black.withOpacity(0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.start,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            Spacer(),
            Container(
              width: Width(context)*0.2,
              height: sy(3),
              margin: EdgeInsets.fromLTRB(sy(0) , sy(4), sy(0), sy(5)),
              decoration: decoration_round(fc_2, sy(5), sy(5), sy(5), sy(5)),),
            Material(
              borderRadius: BorderRadius.only(topLeft: Radius.circular(sy(10)),topRight: Radius.circular(sy(10))),
              child: Container(
                width: Width(context),
                decoration: decoration_round(fc_bg, sy(10), sy(10), 0, 0),
              //  padding: EdgeInsets.fromLTRB(sy(5), sy(10), sy(5), sy(5)),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.start,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [


                    Container(
                      padding: EdgeInsets.fromLTRB(sy(10), sy(10), sy(10), sy(10)),
                      child:  Row(
                        children: [
                          Expanded(child: Mytext('Choose image ', fc_2,bold: true,size: sy(n),padding: sy(5),),),
                          GestureDetector(onTap: (){
                            closeScreen();
                          }, child: Mytext('Done',ThemePrimary)),
                          SizedBox(width: sy(5),),
                        ],
                      ),
                    ),

                    ListAnimation(
                      vertical: true,
                        horizontal: false,
                        children: [
                          Container(
                            width: Width(context),
                            padding: EdgeInsets.fromLTRB(sy(5), sy(5), sy(5), sy(5)),
                            alignment:(widget.showallimage==true)? Alignment.center:null,
                            child:  Wrap(
                              direction: Axis.horizontal,
                              runSpacing: sy(5),
                              spacing: sy(5),
                              children: [

                                GestureDetector(
                                  onTap: (){
                                    getImageFile(ImageSource.camera);
                                  },
                                  child: Container(
                                    width: itemSize,height: itemSize,
                                    child:Container(
                                      child:Icon(Icons.camera_alt,color: Colors.white,size: sy(xl),),
                                      decoration: decoration_round(  ThemePrimary, sy(100), sy(100), sy(100), sy(100)),
                                    ),
                                    padding: EdgeInsets.all( sy(3) ),
                                  ),
                                ),
                                GestureDetector(
                                  onTap: (){
                                    getImageFile(ImageSource.gallery);
                                  },
                                  child: Container(
                                    width: itemSize,height: itemSize,
                                    child:Container(
                                      child:Icon(Icons.image,color: Colors.white,size: sy(xl),),
                                      decoration: decoration_round(  ThemePrimary, sy(100), sy(100), sy(100), sy(100)),
                                    ),
                                    padding: EdgeInsets.all( sy(3) ),
                                  ),
                                ),
                                if(widget.showallimage==true)
                                for(int i=0;i<=22;i++)
                                  GestureDetector(
                                    onTap: (){
                                      setState(() {
                                        result='default/user${i}.jpg';
                                        isDefault=true;
                                        if(widget.url==''){
                                          closeScreen();

                                        }else{

                                          updateToServer();
                                        }


                                        // closeScreen();
                                      });
                                    },
                                    child: Container(
                                      width: itemSize,height: itemSize,
                                      child: ClipRRect(
                                        borderRadius: BorderRadius.circular(sy(200)),
                                        child: Image.asset('assets/images/avatar/user${i}.jpg',width: Width(context)*0.12,height: Width(context)*0.12,),
                                      ),
                                      decoration: decoration_round((result=='default/user${i}.jpg')?ThemePrimary:Colors.transparent, sy(100), sy(100), sy(100), sy(100)),
                                      padding: EdgeInsets.all( sy(3) ),
                                    ),
                                  ),

                              ],
                            ),
                          ),

                    ]),


                    SizedBox(height: sy(10),),


                  ],
                ),
              ),
            ),
          ],
        )
    );
  }

  closeScreen(){
    Navigator.of(context).pop(
        {'result':result,
          'name':'answer2',});
  }

  getImageFile(ImageSource source) async {
    final picker = ImagePicker();

    final image = await picker.pickImage(source: source, imageQuality: 80);
    final img = ImageCropper();
    //Cropping the image

    CroppedFile ? croppedFile = await img.cropImage(
      sourcePath: image!.path,
      aspectRatioPresets: [CropAspectRatioPreset.original],
      uiSettings: [
        AndroidUiSettings(
            toolbarTitle: Lang(" Cropper ", " كروبر "),
            toolbarColor: ThemePrimary,
            toolbarWidgetColor: Colors.white,
            initAspectRatio: CropAspectRatioPreset.original,
            lockAspectRatio: false),
        IOSUiSettings(
          title: Lang(" Cropper ", " كروبر "),),],
      maxWidth: 1500,
      maxHeight: 1000,
    );

    finalImage = File(croppedFile!.path);
    isDefault=false;
    if(widget.url==''){
      closeScreen();

    }else{
      updateToServer();
    }
  }


}

// try {
//   Map results = await Navigator.push(context, TransparantScreen(widget: UploadImage(keys: [], values: [],)));
//   if (results != null) {
//     setState(() {
//       test = results['result'].toString();
//       print('success-$test');
//     });
//   }
// } catch (e) {
//   print('cancel');
// }