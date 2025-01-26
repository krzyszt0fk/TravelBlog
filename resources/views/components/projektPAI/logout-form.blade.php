<form method="POST" action="{{ route('logout') }}">
    @csrf <!-- token , input typu hidden ze specjalnym tokenem zabezpieczajacym -->
    <button type="submit" class="tracking-widest hover:text-stone-500">Wyloguj</button>
</form>
