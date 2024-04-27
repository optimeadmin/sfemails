export type KnpPaginationData = {
  'last': number,
  'current': number,
  'numItemsPerPage': number,
  'first': number,
  'pageCount': number,
  'totalCount': number,
  'pagesInRange': number[],
  'firstPageInRange': number,
  'lastPageInRange': number
}

export type PaginationDataResult<T> = {
  paginationData: KnpPaginationData,
  data: T[]
}