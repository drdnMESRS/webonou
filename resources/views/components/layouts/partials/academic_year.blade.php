@php use App\Actions\Sessions\AcademicYearSession;use Illuminate\Support\Facades\Auth; @endphp
<div>
    <!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->

    <form action="{{ route('change_academic_year') }}" method="POST">
        @csrf
          <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
        <select name="academic_year"
                class="flex items-center justify-center p-2 w-auto rounded-lg bg-indigo-300"
                onchange="this.form.submit()">
            @foreach($academic_years as $year)
                <option value="{{ $year->id }}"
                        @if(((new AcademicYearSession)->get_academic_year() == $year->id)
                        || ($year->est_annee_en_cours))
                            selected="selected"
                    @endif>{{ $year->fullName }}</option>
            @endforeach
        </select>
    </form>
</div>
