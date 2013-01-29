<?php
/*
 * Copyright (C) 2013 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// Dumps received notifications to a text file
// This is where you would do cool stuff based on notifications

$filename = "/tmp/glass-notify.txt";
$file = fopen( $filename, "a+" );
if( $file == false )
{
  echo ( "Error in opening new file" );
  exit();
}
$post_body = file_get_contents('php://input');
if($post_body != null) {
  fwrite( $file, "$post_body\n" );
}
fclose( $file );
