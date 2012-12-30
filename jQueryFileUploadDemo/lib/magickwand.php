<?php
/**
* ����� ��� ��������� ����������� �� �������
* ���������� ���������� MagickWand
*/
abstract class Image {

	/**
	* ���������������� ��������������� �����������
	*
	* �������� � JPEG, PNG, � GIF.
	* ��������������� ������������ ���, ����� ����������� ������ � �������� �������������. ��������� ������ �� ����������
	*
	* @param string ������������ ��������� �����
	* @param string ������������ ��������� �����, ���� ����������� false, �� �������� ���� ���������������� �����
	* @param integer ������ �������������� � px, ���� 0, �� �����������
	* @param integer ������ �������������� � px, ���� 0, �� �����������
	* @param string ��� ��������� ����� jpeg, png ��� gif. ��� null ��� ������������ �� ����� ��������� �����. � ��������� ������ �� ���� ���������
	* @param integer �������� ��������� �����. 1�100 ��� JPEG (������������� 75), 0�9 ��� PNG (������������� 9, ���� ������ �������� �������������� ��������)
	* @param boolean ����� �� ������� ��������. ����������� ������� �� ����� copyright.png
	* @return string ��� ��������� ����� ��� false � ������ ������
	*/
	public function trueresize($file_input, $file_output, $max_w, $max_h, $ext, $quality, $copyright) {

		//������ ������ �� ��������� �����������
		list($w, $h) = getimagesize($file_input);
		if (!$w || !$h) return false; //���������� �������� ����� � ������ �����������
		
		$new_image = NewMagickWand();
		MagickReadImage($new_image, $file_input);
		
		//��������� �����������, ���� ��� ��������� ���������� �������	
		if ($w > $max_w || $h > $max_h) {
			$new_image = MagickTransformImage($new_image, null, $max_w .'x'. $max_h);
			$w = MagickGetImageWidth($new_image);
			$h = MagickGetImageHeight($new_image);
			
		} elseif (!$copyright) {
			return self::convert($file_input, $file_output, null, $ext, $quality); //������ �������� ����
		}
		
		//������ ��������
		if ($copyright) {

			$file_copyright = 'copyright.png';			
			$watermark = NewMagickWand();		
			MagickReadImage($watermark, $file_copyright);
				
			$wc = MagickGetImageWidth($watermark);
			$hc = MagickGetImageHeight($watermark);
			MagickCompositeImage($new_image, $watermark, MW_OverCompositeOp, $w-$wc, $h-$hc);
			
			ClearMagickWand($watermark);
			DestroyMagickWand($watermark);			
		}

		//���������
		return self::convert($file_input, $file_output, $new_image, $ext, $quality);
	}

	/**
	* �������� ���������
	*
	* �������� � JPEG, PNG, � GIF.
	* �������� �� �������� � ������������ �� ������� �������. ���� ������� ������� �� ������, �� ��������� ��� �����������.
	*
	* @param string ������������ ��������� �����
	* @param string ������������ ��������� �����
	* @param integer ������ ������� ��������� � px
	* @param string ��� ��������� ����� jpeg, png ��� gif. ��� null ��� ������������ �� ����� ��������� �����. � ��������� ������ �� ���� ���������
	* @param integer �������� ��������� �����. 1�100 ��� JPEG (������������� 75), 0�9 ��� PNG (������������� 9, ���� ������ �������� �������������� ��������)
	* @param integer ������ ������� ������� � %/100
	* @param integer ������ ������� ������� � %/100
	* @param integer X-���������� ������ �������� ���� ������� ������� � %/100. ��� null ������� ������� ������������ ��-������
	* @param integer Y-���������� ������ �������� ���� ������� ������� � %/100. ��� null ������� ������� ������������ ��-������
	* @return string ��� ��������� ����� ��� false � ������ ������
	*/
	public function makeavatar($file_input, $file_output, $new_size = 100, $ext, $quality, $w_pct = 1, $h_pct = 1, $x_pct, $y_pct) {
	
		//������ ������ �� ��������� �����������
		list($w, $h, $type) = getimagesize($file_input);
		if (!$w || !$h) return false; //���������� �������� ����� � ������ �����������
		
		$new_image = NewMagickWand();
		MagickReadImage($new_image, $file_input);
		
		//��������� ������������ ��������� ������� �������
		if ($w_pct <= 0 || $w_pct > 1) $w_pct = 1;
		if ($h_pct <= 0 || $h_pct > 1) $h_pct = 1;
		if (!is_numeric($x_pct) || $x_pct < 0 || $x_pct >= 1) $x_pct = (1 - $w_pct) / 2;
		if (!is_numeric($y_pct) || $y_pct < 0 || $y_pct >= 1) $y_pct = (1 - $h_pct) / 2;
		
		//��������� �������� � �������
		$src_w = $w*$w_pct;
		$src_h = $h*$h_pct;
		$src_x = min($w*$x_pct, $w-$src_w);
		$src_y = min($h*$y_pct, $h-$src_h);

		//��������� ������� �� ��������
		if ($src_w > $src_h) {
			$src_x += ($src_w - $src_h) / 2;
			$src_w = $src_h;
		} else {
			$src_y += ($src_h - $src_w) / 2;
			$src_h = $src_w;
		}

		//������� ���������
		$new_image = MagickTransformImage($new_image, $src_w . 'x' . $src_h . '+' . $src_x . '+' . $src_y, $new_size . 'x' . $new_size);
		
		//���������
		return self::convert($file_input, $file_output, $new_image, $ext, $quality);
	}
	
	/**
	* ���������� ����������� � JPEG, PNG ��� GIF.
	*
	* �������:
	* convert('img/flower.png', 'thumb/astra.png')                   //��������� img/flower.png ��� ������ thumb/astra.png
	* convert('img/flower.png', 'thumb/astra.jpeg')                  //��������� � ��������������� � jpeg � ��������� �� ���������
	* convert('img/flower.png', 'thumb/',          null,   jpeg)     //��������� � ��������������� � jpeg ��� ������ thumb/flower.jpeg
	* convert('img/flower.png',  null,             null,   jpeg, 70) //����������� � jpeg � ��������� 70
	* convert('img/flower.png',  null,             $image, jpeg)     //�������� $image ��� ������ img/flower.jpeg, ������ ����� ���� img/flower.png 
	* convert( null,            'thumb/astra.png', $image)           //�������� $image ��� ������ 'thumb/astra.png'
	*
	* @param string ��� ��������� �����������
	* @param string ��� ��������� �����������
	* @param object �������� ��������� �����������
	* @param string ��� ��������� ����� jpeg, png ��� gif. ��� null ��� ������������ �� ����� ��������� �����. � ��������� ������ �� ���� ���������
	* @param integer �������� ��������� ����� 1�100. ����� ������������ ������������� �������� 75 ��� JPEG � 100 ��� PNG (��� 100 PNG-����� ����� ������, �� ���������� �������� �� ������)
	* @return string ��� ��������� ����� ��� false � ������ ������
	*/
	public function convert($file_input, $file_output, $image, $ext, $quality) {

		//���������� ��� ��������� �����.
		//���� ��� ����� �� ������, �� ����� ���� �� ����� ��������� �����, ���� �� ��� ���, �� �� ���� ���������
		list($w, $h, $type) = getimagesize($file_input);
		$file_input_ext = image_type_to_extension($type, false);
		$file_output_ext = pathinfo($file_output, PATHINFO_EXTENSION);
		
		if (!$ext && !$file_output_ext) {
			$ext = $file_input_ext;
		} elseif (!$ext) {
			$ext = $file_output_ext;
		}
		
		$ext = strtolower($ext);
		
		//���� �������� ����������� ����, �� ��������� �������������� � ������ ������, ��������� � �������� �������� ���� 
		if (!$image && $file_input_ext != $ext) {
			$image = NewMagickWand();
			if (!MagickReadImage($image, $file_input)) return false; //���� ������������� ����
		}

		//���� ����������� �� ��������� � � ��������� ���� �����������, ������� ������ ����
		if (!$file_output) {
			if ($image) unlink($file_input);
			$file_output = $file_input;
			$fixed = true;
		}
		
		//���������� ��� � ���� ��� ������ �����
		$path = pathinfo($file_output, PATHINFO_DIRNAME).'/';
		$name = pathinfo($file_output, PATHINFO_FILENAME).'.';

		//���� �������������� �� ���������, ������ �������� ����
		if (!$image) {
			if (!$fixed) {
				if (!copy($file_input, $path.$name.$ext)) return false; //�� ������� �����������
			}
			return $name.$ext;
		}
		
		//����������� � ���������
		MagickStripImage($image);//������� �����������
		switch ($ext) {
			case 'jpeg':
			case 'jpg':
				$ext = 'jpeg';
				if ($quality < 0 || $quality > 100) $quality = 75;
				MagickSetImageCompressionQuality($image, $quality);
				MagickSetImageFormat($image, 'jpeg');
				break;
				
			case 'gif':
				MagickSetImageFormat($image, 'gif');
				break;
				
			default:
				$ext = 'png';
				if ($quality < 0 || $quality > 99) $quality = 0;		
				MagickSetImageCompressionQuality($image, $quality);
				MagickSetImageFormat($image, 'png');		
		}

		if (!MagickWriteImage($image, $path.$name.$ext)) return false; //�� ������� ��������� ����
		
		ClearMagickWand($image);
		DestroyMagickWand($image);
		
		return $name.$ext;
	}
}
?>