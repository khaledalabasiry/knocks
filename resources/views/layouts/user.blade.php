<!DOCTYPE html>
<html class = "knocks_bg_color_kit">


  <head>
    <meta charset="UTF-8" />
    <!--Internet Explorer Comp -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile First -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="user_lang" content = "{{  auth()->user()->userLanguage() }}"/>
    <meta name="lang_font" content = "{{  auth()->user()->userLanguageFont() }}"/>
    <meta name="lang_alignment" content = "{{  auth()->user()->userLanguageAlignment() }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="pp_index" content="{{ auth()->user()->profile_picture }}">
    <meta name="user" content="{{auth()->user()->id}}">
    <meta name="main_circle" content="{{auth()->user()->mainCircle()->id}}">
{{--     <meta http-equiv="Content-Security-Policy" 
    content="default-src *;
   img-src * 'self' data: https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' *;
   style-src  'self' 'unsafe-inline' *"> --}}
    <?php auth()->user()->updateToken(csrf_token()); ?>
    <link rel = "stylesheet" href = {{asset('css/app.css')}}  />
    <meta name="session-type" content="user">
    <audio src = "{{asset('snaps/knocks_notification.mp3')}}" id = "knocks_notification" style = "display : none" controls></audio>
    <audio src = "{{asset('snaps/knocks_recording.mp3')}}" id = "knocks_recording_vid_src" style = "display : none" controls></audio>
    <audio src = "{{asset('snaps/knocks-react.mp3')}}" id = "knocks_disreact_vid_src" style = "display : none" controls></audio>
    <audio src = "{{asset('snaps/knocks-disreact.mp3')}}" id = "knocks_react_vid_src" style = "display : none" controls></audio>
    <link rel="icon" type="text/css" href="{{asset('snaps/knocks.png')}}">


    <style>
      html , body , .knocks_language_default_font{
        font-family : {{  auth()->user()->userLanguageFont() }} ;
        text-align : {{  auth()->user()->userLanguageAlignment() }} ;
      }
      .knocks_language_font{
        font-family : {{  auth()->user()->userLanguageFont() }} ;
      }
      .knocks_language_default_float{
        float : {{  auth()->user()->userLanguageAlignment() }} ;
      }
    </style>
        <div id="knocks_nav_vue">
          @include('layouts.usernav')
          @include('layouts.rightnav')
        </div>
  </head>
  <body>
    <main id = "knocks_main" style= "background-color: #eceff1">
      <div class = "knocks_full_wrapper"></div>
      <div id = "app"  class = "row" style="margin-bottom: 0px;">


        <!--Notification-->
        <div class = "knocks_balloons_container" >
        <knocksballon
        v-for = "(ballon , ind) in ballons" :key="ind"
        :gid="'knocks_notification_wall_'+ind"   
        v-if = "ballon.poped == 0" 
        :constrains = "ballon"
        ></knocksballon>

      {{--   <knocksballon
        
        :gid="'knocks_notification_wall_'"
        category = "System"
        
        :constrains = "{
         content : '<h3>Hello</h3>' , 
        }"

        ></knocksballon> --}}


      </div>

  
     <div>
       @yield('content')




       </div>
      </div>
    </main>
    @include('layouts.footer')
  </body>


  <script src = "{{asset('js/app.js')}}"></script>
</html>
