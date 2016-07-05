<footer class="footer">
    <div class="container top-buffer">

        <div class="row">
            <div class="col-sm-2 hidden-xs">
                <img class="footer-brand-logo img-responsive" alt="" src="{{ config('shop.logo') }}" width="75%">
            </div>
            <div class="col-sm-5 col-xs-12">
                <div class="row">
                    <div class="col-xs-4">
                        <ul class="footer-list">
                            <li>
                                <h6 class="footer-heading">{{ config('shop.name') }}</h6>
                            </li>
                            <li><a href="/faqs">Help</a></li>
                            <li><a href="/terms-and-conditions">Terms &amp; Privacy</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-4">
                        <ul class="footer-list">
                            <li>
                                <h6 class="footer-heading">Company</h6>
                            </li>
                            <li><a href="/about">About</a></li>
                            <li><a href="/contact">Contact</a></li>
                            <li><a href="/press">Press</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-4">
                        <ul class="footer-list">
                            <li>
                                <h6 class="footer-heading">Discover</h6>
                            </li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Events</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-sm-offset-1">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="footer-social-buttons">
                            <li>
                                <a target="_blank" rel="nofollow" href="https://twitter.com"><i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" rel="nofollow" href="http://www.facebook.com"><i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" rel="nofollow" href="https://www.instagram.com"><i class="fa fa-instagram"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" rel="nofollow" href="http://houzz.com"><i class="fa fa-houzz"></i>
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
