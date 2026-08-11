<form action="{{ route('leads.update', $lead) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="title" value="{{ $lead->title }}">
    <button type="submit">Update</button>
</form>