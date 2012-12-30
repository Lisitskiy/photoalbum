<?php
/**
* ����� ��� ��������� ����������� �� �������
* ���������� ���������� Imagick
*
* ��������! ������� �� ��������
*/
class Image {

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
		
		//return $name.$ext;
	}
}
/* ��� ������ ������������������
	$start_time = microtime(true);

	//��������

	$exec_time = microtime(true) - $start_time;
	file_put_contents('log.txt', $exec_time."\n", FILE_APPEND);
*/
?>