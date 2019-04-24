<footer class="container mx-auto px-4 py-6">
    <div class="md:flex justify-between">
        <div class="flex mb-4">
            <div class="mr-10 hidden md:block">
                <img class="h-32 w-32" alt="" src="{{ config('shop.logo') }}">
            </div>
            <div class="pr-10 w-1/2 md:w-auto">
                <ul class="footer-list">
                    <li>
                        <h6 class="footer-heading">{{ config('shop.name') }}</h6>
                    </li>
                    @if (isset($menuItems['footer_left']))
                        @foreach($menuItems['footer_left'] as $item)
                        <li><a class="no-underline text-white hover:text-grey-light" href="{{ $item->link }}">{{ $item->label }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="w-1/2 md:w-auto">
                <ul class="footer-list">
                    <li>
                        <h6 class="footer-heading">Company</h6>
                    </li>
                    @if (isset($menuItems['footer_right']))
                        @foreach($menuItems['footer_right'] as $item)
                        <li><a class="no-underline text-white hover:text-grey-light" href="{{ $item->link }}">{{ $item->label }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>


        <div>

                <ul class="list-reset md:text-right text-center">
                    @foreach([
                        ['url' => 'https://twitter.com/samarkanddesign', 'icon' => 'twitter'],
                        ['url' => 'https://facebook.com/samarkanddesign', 'icon' => 'facebook'],
                        ['url' => 'https://instagram.com/samarkanddesign', 'icon' => 'instagram'],
                        ['url' => 'https://houzz.co.uk/user/hillygrumbar', 'icon' => 'houzz'],
                        ['url' => 'https://pinterest.com/samarkanddesign', 'icon' => 'pinterest'],
                    ] as $socialLink)
                    <li class="inline-block">
                    <a target="_blank" class="flex h-8 w-8 border justify-center items-center no-underline text-white hover:text-grey-light" rel="nofollow" href="{{ $socialLink['url'] }}"><i class="fa fa-{{ $socialLink['icon'] }}"></i>
                        </a>
                    </li>
                    @endforeach
                </ul>




                <div class="md:text-right text-center text-grey mt-4">
                    <p>
                        <small>&copy; {{ date('Y') }} {{ config('shop.name') }}. ALL RIGHTS RESERVED<br>
                            Errors and omissions accepted.</small>
                        </p>
                    </div>
                </div>

        </div>
    </div>

    </footer>
