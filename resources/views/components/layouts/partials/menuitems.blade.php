<flux:navlist variant="outline" >
    <flux:navlist.group :heading="__('Platform')" class="grid">
        <flux:navlist.item
            icon="home"
            :href="route('dashboard')"
            :current="request()->routeIs('dashboard')"
            wire:navigate>
            {{ __('Dashboard') }}
        </flux:navlist.item>


        @foreach($menuItems as $key=>$item)
            <!-- display a group for the module -->
            <flux:navlist.group heading="{{$key}}" expandable :expanded="false"  >

                @foreach($item as $it)
                    <flux:navlist.item>
                        <a href="{{ $it->url }}" >
                            <i class="{{ $it->fonction_icon }}"></i>
                            {{ $it->fonction_name }}
                        </a>
                    </flux:navlist.item>
                @endforeach
            </flux:navlist.group>
        @endforeach
    </flux:navlist.group>
</flux:navlist>
