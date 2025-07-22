
    <div class="flex justify-end">

<button
    wire:click="exportExcel"
    wire:loading.attr="disabled"
    wire:target="exportExcel"
    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none
           focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600
           dark:hover:bg-green-700 dark:focus:ring-green-800 flex items-center justify-center gap-2"
>
    <!-- Excel Icon -->
    <svg xmlns="http://www.w3.org/2000/svg"
         class="h-5 w-5 text-white"
         viewBox="0 0 384 512"
         fill="currentColor">
        <path d="M369.9 97.98l-83.88-83.88C275.6 5.373 263.8 0 251.4 0H64C28.65 0 0 28.65 0 64v384c0 35.35
                 28.65 64 64 64h256c35.35 0 64-28.65 64-64V132.6C384 120.2 378.6 108.4 369.9 97.98zM256 52l76.1
                 76.1H256V52zM272 368h-40l-32-48-32 48H128l48-72-48-72h40l32 48 32-48h40l-48 72L272 368z"/>
    </svg>

    Exporter

    <!-- Loading Spinner -->
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

