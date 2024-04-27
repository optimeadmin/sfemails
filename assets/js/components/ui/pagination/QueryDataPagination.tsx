import React from 'react'
import { KnpPagination } from './KnpPagination'
import { useQueryStringData } from '../../../hooks/queryStringData'
import { KnpPaginationData } from './types'

type Props = {
  paginationData: KnpPaginationData | null
}

export function QueryDataPagination ({ paginationData }: Props) {
  const { queryData, setQueryData } = useQueryStringData()

  function setPage (page: number) {
    setQueryData({ ...queryData, page: String(page) })
  }

  return (
    <KnpPagination paginationData={paginationData} setPage={setPage}/>
  )
}