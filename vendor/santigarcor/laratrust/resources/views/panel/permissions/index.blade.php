@extends('laratrust::panel.layout')

@section('title', 'Permissions')

@section('content')
  <div class="flex flex-col">
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
      <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
        <table class="min-w-full">
          <thead>
            <tr>
              <th class="th">Id</th>
              <th class="th">Name/Code</th>
              <th class="th">Display Name</th>
              <th class="th">Description</th>
              <th class="th"></th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach ($permissions as $permission)
            <tr>
              <td class="td text-sm leading-5 text-gray-900">
                {{$permission->id}}
              </td>
              <td class="td text-sm leading-5 text-gray-900">
                {{$permission->name}}
              </td>
              <td class="td text-sm leading-5 text-gray-900">
                {{$permission->display_name}}
              </td>
              <td class="td text-sm leading-5 text-gray-900">
                {{$permission->description}}
              </td>
              <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                <a href="{{route('laratrust.permissions.edit', $permission->id)}}" class="text-blue-600 hover:text-blue-900">Edit</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{ $permissions->links('laratrust::panel.pagination') }}
@endsection