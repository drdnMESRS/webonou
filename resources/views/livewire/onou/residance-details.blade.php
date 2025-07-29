<div>

    <div class="text-center">
        <livewire:common.loader/>
    </div>

    <div x-data="{ showResidenceDetails: @entangle('showResidenceDetails') }"
         class="container mx-auto p-4">
        <div x-show="showResidenceDetails" class="residence-details ">
            <div class="md:flex bg-white
             dark:bg-gray-800
             rounded-lg shadow-lg w-full">
                <!-- Tab Navigation (Left Sidebar) -->
                <!-- The ul element now has data-tabs-toggle to link to the content container -->
                <ul class="flex-column space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400 md:me-4 mb-4 md:mb-0 p-4 md:p-6 border-b md:border-b-0 md:border-e border-gray-200 dark:border-gray-700"
                    id="vertical-pills-tab" data-tabs-toggle="#vertical-pills-tabcontent" role="tablist">
                    <li>
                        <!-- Tab button for Profile. Note the data-tabs-target and id for Flowbite's JS. -->
                        <button type="button" class="inline-flex items-center px-4 py-3 text-white
                         bg-blue-700 rounded-lg w-full dark:bg-blue-600 active"
                                id="profile-tab" data-tabs-target="#profile-content"
                                role="tab" aria-controls="profile-content" >
                            <!-- Profile Icon -->
                            <svg class="w-4 h-4 me-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                            </svg>
                            {{__('views/livewire/onou/forms/residence_details.main_informations')}}
                        </button>
                    </li>

                    <li>
                        <!-- Tab button for Dashboard -->
                        <button type="button" class="inline-flex items-center px-4 py-3
                         rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full
                         dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white"
                                id="content-tab"
                                data-tabs-target="#content-content"
                                role="tab"
                                aria-controls="content-content"
                                aria-selected="false">
                            <!-- Dashboard Icon -->
                            <svg class="w-4 h-4 me-2 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18"><path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/></svg>
                            {{__('views/livewire/onou/forms/residence_details.more_detailes')}}
                        </button>
                    </li>

                </ul>

                <!-- Tab Content Container -->
                <!-- This div wraps all tab content panels and is linked by data-tabs-toggle -->
                <div id="vertical-pills-tabcontent" class="p-6 w-full">
                    <!-- Profile Content -->
                    <!-- The 'active' class on the first content div will be managed by Flowbite's JS, along with 'hidden' -->
                    <div id="profile-content" class="p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full"
                         role="tabpanel" aria-labelledby="profile-tab">
                        <h3 class="text-lg font-bold
                        text-gray-900 dark:text-white mb-2">{!! $residence['full_name'] ?? '' !!}</h3>

                        <x-pages.residances.details-table :rows="
                                            [
                                                [__('views/livewire/onou/forms/residence_details.code'), $residence['identifiant'] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.type'), $residence[''] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.sous_type'), $residence['type_nc_full_name'] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.appartenance'), $residence['appartenance'] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.etat'), $residence['etat_strecture_full_name'] ?? ' ']
                                            ]" />
                    </div>
                    <div id="content-content" class="hidden p-6 bg-gray-50 text-medium
                        text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full"
                         role="tabpanel" aria-labelledby="content-tab">
                        <h3 class="text-lg font-bold   text-gray-900 dark:text-white mb-2"> {{__('views/livewire/onou/forms/residence_details.more_detailes')}}</h3>
                        <x-pages.residances.details-table :rows="
                                            [
                                                [__('views/livewire/onou/forms/residence_details.code'), $residence['identifiant'] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.date_creation'), $residence['date_creation'] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.date_ouverture'), $residence['date_ouverture'] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.numero_inscription'), $residence['numero_inscription_domaniale'] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.date_inscription'), $residence['date_inscription_domaniale'] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.capacite_theorique'), $residence['capacite_theorique'] ?? 0],
                                                [__('views/livewire/onou/forms/residence_details.capacite_reel'), $residence['capacite_reel'] ?? 0],
                                                [__('views/livewire/onou/forms/residence_details.nbt_places_garçons'), $residence['places_disponibles_garcons'] ?? 0],
                                                [__('views/livewire/onou/forms/residence_details.nbr_places_filles'), $residence['places_disponibles_filles'] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.surface_globale'), $residence['surface_globale'] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.surface_batie'), $residence['surface_batie'] ?? ''],
                                                [__('views/livewire/onou/forms/residence_details.consistance'), $residence['consistance'] ?? ''],
                                            ]" />
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


