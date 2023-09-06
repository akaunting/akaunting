@extends('laratrust::panel.layout')

@section('title', 'Roles')

@section('content')
  <div class="flex flex-col">
    <a
      href="{{route('laratrust.roles.create')}}"
      class="self-end bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
    >
      + New Role
    </a>
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
      <div class="mt-4 align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
        <table class="min-w-full">
          <thead>
            <tr>
              <th class="th">Id</th>
              <th class="th">Display Name</th>
              <th class="th">Name</th>
              <th class="th"># Permissions</th>
              <th class="th"></th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach ($roles as $role)
            <tr>
              <td class="td text-sm leading-5 text-gray-900">
                {{$role->getKey()}}
              </td>
              <td class="td text-sm leading-5 text-gray-900">
                {{$role->display_name}}
              </td>
              <td class="td text-sm leading-5 text-gray-900">
                {{$role->name}}
              </td>
              <td class="td text-sm leading-5 text-gray-900">
                {{$role->permissions_count}}
              </td>
              <td class="flex justify-end px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                @if (\Laratrust\Helper::roleIsEditable($role))
                <a href="{{route('laratrust.roles.edit', $role->getKey())}}" class="text-blue-600 hover:text-blue-900">Edit</a>
                @else
                <a href="{{route('laratrust.roles.show', $role->getKey())}}" class="text-blue-600 hover:text-blue-900">Details</a>
                @endif
                <form
                  action="{{route('laratrust.roles.destroy', $role->getKey())}}"
                  method="POST"
                  onsubmit="return confirm('Are you sure you want to delete the record?');"
                >
                  @method('DELETE')
                  @csrf
                  <button
                    type="submit"
                    class="{{\Laratrust\Helper::roleIsDeletable($role) ? 'text-red-600 hover:text-red-900' : 'text-gray-600 hover:text-gray-700 cursor-not-allowed'}} ml-4"
                    @if(!\Laratrust\Helper::roleIsDeletable($role)) disabled @endif
                  >Delete</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{ $roles->links('laratrust::panel.pagination') }}
@endsection
