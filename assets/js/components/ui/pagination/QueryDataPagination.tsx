import React from 'react'
import { KnpPagination } from './KnpPagination'
import { useQueryStringData } from '../../../hooks/queryStringData'
import { KnpPaginationData } from './types'
import { PaginationProps } from 'react-bootstrap'

type Props = {
  paginationData: KnpPaginationData | null
  className?: string,
}

export function QueryDataPagination ({ paginationData, className }: Props) {
  const { queryData, setQueryData } = useQueryStringData()

  function setPage (page: number) {
    setQueryData({ ...queryData, page: String(page) })
  }

  return (
    <KnpPagination paginationData={paginationData} setPage={setPage} className={className}/>
  )
}