 <header class="py-3 px-5 d-flex justify-content-between align-items-center">
     <div class="logo">
         <a href="{{ route('home') }}"><img src="{{ asset('assets/website/images/logo2.png') }}" alt="" /></a>

     </div>

     <nav>
         <ul class="d-flex gap-4 list-unstyled">
             <li><a href="{{ route('home') }}" target="_blank">Home</a></li>
             <li><a href="https://vpschain.com/pricing" target="_blank">Buy Windows VPS</a></li>
             <li><a href="https://vpschain.com/pricing" target="_blank">Buy Linux VPS</a></li>
             <li><a href="https://vpschain.com/pricing" target="_blank">Buy VPS With BTC</a></li>
         </ul>
     </nav>

 </header>
 <div class="banner">
     <h1>@yield('banner') - System Hint</h1>
 </div>
