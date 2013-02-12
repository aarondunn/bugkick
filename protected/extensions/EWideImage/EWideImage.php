<?php


Yii::setPathOfAlias('EWideImage', dirname(__FILE__));

Yii::import('EWideImage.WideImage');
Yii::import('EWideImage.WideImage_Exception');
Yii::import('EWideImage.WideImage_Image');
Yii::import('EWideImage.WideImage_TrueColorImage');
Yii::import('EWideImage.WideImage_PaletteImage');
Yii::import('EWideImage.WideImage_Coordinate');
Yii::import('EWideImage.WideImage_Canvas');
Yii::import('EWideImage.WideImage_MapperFactory');
Yii::import('EWideImage.WideImage_OperationFactory');

Yii::import('EWideImage.Font.WideImage_Font_GDF');
Yii::import('EWideImage.Font.WideImage_Font_PS');
Yii::import('EWideImage.Font.WideImage_Font_TTF');

Yii::import('EWideImage.Mapper.WideImage_Mapper_BMP');
Yii::import('EWideImage.Mapper.WideImage_Mapper_GD');
Yii::import('EWideImage.Mapper.WideImage_Mapper_GD2');
Yii::import('EWideImage.Mapper.WideImage_Mapper_GIF');
Yii::import('EWideImage.Mapper.WideImage_Mapper_JPEG');
Yii::import('EWideImage.Mapper.WideImage_Mapper_PNG');
Yii::import('EWideImage.Mapper.WideImage_Mapper_TGA');

Yii::import('EWideImage.Operation.WideImage_Operation_AddNoise');
Yii::import('EWideImage.Operation.WideImage_Operation_ApplyConvolution');
Yii::import('EWideImage.Operation.WideImage_Operation_ApplyFilter');
Yii::import('EWideImage.Operation.WideImage_Operation_ApplyMask');
Yii::import('EWideImage.Operation.WideImage_Operation_AsGrayscale');
Yii::import('EWideImage.Operation.WideImage_Operation_AsNegative');
Yii::import('EWideImage.Operation.WideImage_Operation_AutoCrop');
Yii::import('EWideImage.Operation.WideImage_Operation_CopyChannelsPalette');
Yii::import('EWideImage.Operation.WideImage_Operation_CopyChannelsTrueColor');
Yii::import('EWideImage.Operation.WideImage_Operation_CorrectGamma');
Yii::import('EWideImage.Operation.WideImage_Operation_Crop');
Yii::import('EWideImage.Operation.WideImage_Operation_Flip');
Yii::import('EWideImage.Operation.WideImage_Operation_GetMask');
Yii::import('EWideImage.Operation.WideImage_Operation_Merge');
Yii::import('EWideImage.Operation.WideImage_Operation_Mirror');
Yii::import('EWideImage.Operation.WideImage_Operation_Resize');
Yii::import('EWideImage.Operation.WideImage_Operation_ResizeCanvas');
Yii::import('EWideImage.Operation.WideImage_Operation_Rotate');
Yii::import('EWideImage.Operation.WideImage_Operation_RoundCorners');
Yii::import('EWideImage.Operation.WideImage_Operation_Unsharp');

Yii::import('EWideImage.vendor.de77.WideImage_vendor_de77_TGA');
Yii::import('EWideImage.vendor.de77.WideImage_vendor_de77_BMP');

/**
 * @author Luke Jurgs
 * @version 0.0.1-2012-06-15
 */
class EWideImage extends WideImage {

}