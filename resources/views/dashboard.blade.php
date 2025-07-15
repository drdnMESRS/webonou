<x-layouts.app :title="__('Dashboard')">
    <x-common.container>
        <x-common.grid :gap="2" :columns="4">
            <x-common.card>
                <x-common.stat-card :icon="'<svg class=\'w-6 h-6 text-gray-500 dark:text-gray-400\' aria-hidden=\'true\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'currentColor\' viewBox=\'0 0 20 19\'><path d=\'M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z\'/><path d=\'M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z\'/></svg>'" :stat="$stathb['total']" :description="'Demande'"
                    :badgeIcon="'<svg class=\'w-6 h-6 text-blue-500 \' viewBox=\'0 0 24 24\' fill=\'none\'
     xmlns=\'http://www.w3.org/2000/svg\'>
  <circle cx=\'12\' cy=\'12\' r=\'9\' stroke=\'currentColor\' stroke-width=\'2\' class=\'opacity-25\'/>
  <path d=\'M8 12h8\' stroke=\'currentColor\' stroke-width=\'3\' stroke-linecap=\'round\' class=\'opacity-75\'
        d=\'M12 6v6l4 2\'/>
</svg>'"
                    :badgeText="'Total'" :bgColor="'bg-blue-50 dark:bg-gray-700'"
                      :class="'bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-blue-900 dark:text-blue-300'">
                    <!-- Additional content here -->
                </x-common.stat-card>
            </x-common.card>
            <x-common.card>
                <x-common.stat-card :icon="'<svg class=\'w-6 h-6 text-gray-500 dark:text-gray-400\' aria-hidden=\'true\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'currentColor\' viewBox=\'0 0 20 19\'><path d=\'M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z\'/><path d=\'M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z\'/></svg>'"
                     :stat="$stathb['pending']" :description="'Demande'"
                    :badgeIcon="'<svg class=\'w-6 h-6 text-yellow-500 \' viewBox=\'0 0 24 24\' fill=\'none\'
     xmlns=\'http://www.w3.org/2000/svg\'>
  <circle cx=\'12\' cy=\'12\' r=\'9\' stroke=\'currentColor\' stroke-width=\'2\' class=\'opacity-25\'/>
  <path stroke=\'currentColor\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'3\' class=\'opacity-75\'
        d=\'M12 6v6l4 2\'/>
</svg>'"
                    :badgeText="'Pending'" :bgColor="'bg-yellow-50 dark:bg-gray-700'"

                    :class="'bg-yellow-100 text-yellow-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-yellow-900 dark:text-yellow-300'">

                    <!-- Additional content here -->
                </x-common.stat-card>
            </x-common.card>
            <x-common.card>

                <x-common.stat-card :icon="'<i class=\'fa fa-check-circle text-green-500 dark:text-green-400\'></i>'" :stat="$stathb['accepted']" :description="'Demande'"
                    :badgeIcon="'<svg class=\'w-6 h-6 text-green-500\' fill=\'none\' viewBox=\'0 0 24 24\'
     xmlns=\'http://www.w3.org/2000/svg\'>
  <circle cx=\'12\' cy=\'12\' r=\'9\' stroke=\'currentColor\' stroke-width=\'2\' class=\'opacity-25\' />
  <path d=\'M9 12l2 2 4-4\' stroke=\'currentColor\' stroke-width=\'3\' stroke-linecap=\'round\' stroke-linejoin=\'round\' class=\'opacity-75\'
        d=\'M12 6v6l4 2\'/>
</svg>'"
                    :badgeText="'Acceptée'" :bgColor="'bg-green-50 dark:bg-gray-700'"
                      :class="'bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300'">

                </x-common.stat-card>
            </x-common.card>

            <x-common.card>
                <x-common.stat-card :icon="'<svg class=\'w-6 h-6 text-gray-500 dark:text-gray-400\' aria-hidden=\'true\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'currentColor\' viewBox=\'0 0 20 19\'><path d=\'M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z\'/><path d=\'M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z\'/></svg>'" :stat="$stathb['rejected']" :description="'demande'"
                    :badgeIcon="'<svg class=\'w-6 h-6 text-red-500\' fill=\'none\' viewBox=\'0 0 24 24\'
     xmlns=\'http://www.w3.org/2000/svg\'>
  <circle cx=\'12\' cy=\'12\' r=\'9\' stroke=\'currentColor\' stroke-width=\'2\' class=\'opacity-25\'/>
  <path d=\'M15 9l-6 6M9 9l6 6\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\' class=\'opacity-75\'
        d=\'M12 6v6l4 2\'/>
</svg>'"
                    :badgeText="'Rejected'" :bgColor="'bg-red-50 dark:bg-gray-700'"
                      :class="'bg-red-100 text-red-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-red-900 dark:text-red-300'">
                    <!-- Additional content here -->
                </x-common.stat-card>
            </x-common.card>

        </x-common.grid>
        <br>
          <livewire:tables.onou-cm-stat-table />
    </x-common.container>


</x-layouts.app>
