<div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
  <div class="flex-1 sm:flex sm:items-center sm:justify-end">
    <div>
      <span class="relative z-0 inline-flex shadow-sm">
        <{!!$paginator->onFirstPage()
          ? 'span'
          : 'a href="' . $paginator->previousPageUrl() . '"'
          !!}
          class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium {{$paginator->onFirstPage() ? 'text-gray-500' : 'text-gray-700'}} hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
        >
          <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
          </svg>
          Previous
        </{{$paginator->onFirstPage() ? 'span' : 'a' }}>
        <{!!$paginator->hasMorePages()
            ? 'a href="' . $paginator->nextPageUrl() . '"'
            : 'span'
          !!}
          class="-ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium {{$paginator->hasMorePages() ? 'text-gray-700' : 'text-gray-500'}} hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
        >
            Next
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
        </{{ $paginator->hasMorePages() ? 'a' : 'span' }}>
      </span>
    </div>
  </div>
</div>