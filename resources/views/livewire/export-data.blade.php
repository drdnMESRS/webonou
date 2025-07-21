
    <div class="flex justify-end">

<button
    wire:click="exportExcel"
    wire:loading.attr="disabled"
    wire:target="exportExcel"
    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none
           focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600
           dark:hover:bg-green-700 dark:focus:ring-green-800 flex items-center justify-center gap-2"
>
    Exporter

    <svg
        wire:loading
        wire:target="exportExcel"
        class="animate-spin h-4 w-4 text-white"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
    >
        <circle class="opacity-25" cx="12" cy="12" r="10"
                stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>
</button>
</div>

