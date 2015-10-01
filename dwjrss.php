<?php
// RSS を取得する対象
$sites = array( "opensource", "java", "cloud", "linux", "xml", "mobile" );

// 強制ダウンロード指定
header( 'Content-Type: application/force-download;' );
header( 'Content-Disposition: attachment; filename="dwj.csv"' );

for( $i = 0; $i < count( $sites ); $i ++ ){
  // RSS の URL
  $url = "http://www.ibm.com/developerworks/jp/views/" . $sites[$i] . "/rss/libraryview.jsp";
  $rss = file_get_contents( $url );
  $xml = simplexml_load_string( $rss );
  $items = $xml->channel->item;
  for( $j = 0; $j < count( $items ); $j ++ ){
    $item = $items[$j];
    $description = $item->description;

    // カンマを大文字に＆改行を無視
    $description = str_replace( ",", "，", $description );
    $description = str_replace( "\n", "", $description );
    $description = str_replace( "\r", "", $description );

    $len = mb_strlen( $description );
    if( $len > 0 ){
      // $description が 1024 文字を超えていたら強制的に切る
      if( $len > 1024 ){
        $description = mb_substr( $description, 0, 1024 );
      }

      // CSV のレコードを作成
      $line = $description . "," . $sites[$i] . "\n";
      echo $line;
    }
  }
}
@ob_flush();
@flush();
exit();
?>

