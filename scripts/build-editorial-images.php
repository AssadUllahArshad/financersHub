<?php
// Original editorial diagrams; no third-party photographs or logos.
$root=dirname(__DIR__);$directory=$root.'/public/uploads/images/editorial';
if(!is_dir($directory)){mkdir($directory,0775,true);}
$font=file_exists('C:/Windows/Fonts/arial.ttf')?'C:/Windows/Fonts/arial.ttf':'/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
if(!file_exists($font)){throw new RuntimeException('Install an Arial or DejaVu Sans font to rebuild the diagrams.');}
$sets=[
 'emergency'=>['EMERGENCY SAVINGS','A first target, one contribution at a time',[150,225,300,375,450,525,600],['Start','Month 1','Month 2','Month 3','Month 4','Month 5','Month 6'],'Illustration: starting at 150 and adding 75 monthly. No interest.'],
 'compound'=>['MONTHLY COMPOUNDING','See deposits and time work together',[1000,5036.81,9725.44,17175.24],['Start','Year 3','Year 6','Year 10'],'Illustration: 1,000 + 100/month at 5% nominal annual interest.'],
 'fees'=>['THE COST OF FEES','Ask what you pay, when, and why',[25297.5,21911.23],['0.25% annual fee','1% annual fee'],'Illustration: 10,000 x (1 + 5% - fee)^20. Not a forecast.'],
];
foreach($sets as $key=>[$title,$subtitle,$values,$labels,$note]){
 foreach(['cover','body'] as $variant){
  $im=imagecreatetruecolor(1400,800);$bg=imagecolorallocate($im,14,48,48);$white=imagecolorallocate($im,243,247,241);$muted=imagecolorallocate($im,173,207,193);$gold=imagecolorallocate($im,220,181,94);$teal=imagecolorallocate($im,73,153,130);
  imagefill($im,0,0,$bg);imagettftext($im,18,0,70,65,$muted,$font,'FINANCERSHUB / EXPLAINERS');imagettftext($im,42,0,70,155,$white,$font,$title);imagettftext($im,24,0,70,205,$muted,$font,$subtitle);
  if($variant==='cover'){
   for($i=0;$i<5;$i++){ $x=780+$i*95;$h=90+$i*60;imagefilledrectangle($im,$x,650-$h,$x+58,650,$i===4?$gold:$teal); }
   imagettftext($im,28,0,70,380,$white,$font,'CLEAR QUESTIONS.');imagettftext($im,28,0,70,430,$white,$font,'WORKED EXAMPLES.');imagettftext($im,28,0,70,480,$white,$font,'SOURCES YOU CAN CHECK.');
   imagettftext($im,18,0,70,730,$muted,$font,'Educational illustration / Not personalized financial advice');
  }else{
   $max=max($values);$step=1200/count($values);
   foreach($values as $i=>$value){$x=(int)(100+$i*$step);$width=(int)min(180,$step-35);$height=(int)(300*$value/$max);imagefilledrectangle($im,$x,610-$height,$x+$width,610,$i===count($values)-1?$gold:$teal);imagettftext($im,19,0,$x,590-$height,$white,$font,number_format($value,($key==='compound'?2:0)));imagettftext($im,16,0,$x,650,$muted,$font,$labels[$i]);}
   imagettftext($im,17,0,70,740,$muted,$font,$note);
  }
  imagewebp($im,$directory.'/'.$key.'-'.$variant.'.webp',82);imagedestroy($im);
 }
}
echo "Created six original WebP editorial diagrams.\n";
