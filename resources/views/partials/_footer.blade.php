<footer class="footer">
    <div class="container">

        <div class="row">
            <div class="col-sm-2 hidden-xs">
                <img class="footer-brand-logo img-responsive" alt="" src="{{ config('shop.logo') }}" width="75%">
            </div>
            <div class="col-sm-5 col-xs-12">
                <div class="row">
                    <div class="col-xs-6">
                        <ul class="footer-list">
                            <li>
                                <h6 class="footer-heading">{{ config('shop.name') }}</h6>
                            </li>
                            @if (isset($menuItems['footer_left']))
                              @foreach($menuItems['footer_left'] as $item)
                                <li><a href="{{ $item->link }}">{{ $item->label }}</a></li>
                              @endforeach
                            @endif
                        </ul>
                    </div>
                    <div class="col-xs-6">
                        <ul class="footer-list">
                            <li>
                                <h6 class="footer-heading">Company</h6>
                            </li>
                            @if (isset($menuItems['footer_right']))
                              @foreach($menuItems['footer_right'] as $item)
                                <li><a href="{{ $item->link }}">{{ $item->label }}</a></li>
                              @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-sm-offset-1">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="footer-social-buttons">
                            <li>
                                <a target="_blank" rel="nofollow" href="https://twitter.com/samarkanddesign"><i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" rel="nofollow" href="https://www.facebook.com/samarkanddesign"><i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" rel="nofollow" href="https://www.instagram.com/samarkanddesign/"><i class="fa fa-instagram"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" rel="nofollow" href="https://www.houzz.co.uk/user/hillygrumbar"><i class="fa fa-houzz"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" rel="nofollow" href="https://www.pinterest.com/samarkanddesign/"><i class="fa fa-pinterest"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-sm-12">
                        <div class="legals text-right">
                            <p>
                                <small>&copy; {{ date('Y') }} {{ config('shop.name') }}. ALL RIGHTS RESERVED<br>
                                    Errors and omissions accepted.</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </footer>
