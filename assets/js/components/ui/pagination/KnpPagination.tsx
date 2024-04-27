import React, { useId, useRef } from 'react'
import { FormControl, FormLabel, Pagination as RP } from 'react-bootstrap'
import { KnpPaginationData } from './types'

type KnpPaginationProps = {
  paginationData?: KnpPaginationData | null,
  setPage: (page: number) => void,
  pageParamName?: string
}

export function KnpPagination ({ paginationData, setPage, pageParamName = 'page' }: KnpPaginationProps) {
  if (!paginationData) return null

  const { first, last, current } = paginationData
  const { pagesInRange, lastPageInRange, firstPageInRange } = paginationData

  if (!current || last === 1) return null

  return (
    <div className="d-flex gap-2 align-items-center">
      <RP className="mb-0">
        <RP.Item disabled={current === 1} onClick={() => setPage(current - 1)}>&laquo;</RP.Item>

        {first < firstPageInRange && (
          <RP.Item onClick={() => setPage(first)}>{first}</RP.Item>
        )}
        {firstPageInRange === 3 && (
          <RP.Item onClick={() => setPage(2)}>2</RP.Item>
        )}
        {firstPageInRange > 3 && (
          <RP.Item disabled>...</RP.Item>
        )}

        {pagesInRange?.map(page => (
          <RP.Item key={page} active={current === page} onClick={() => setPage(page)}>{page}</RP.Item>
        ))}

        {(last - 2) > lastPageInRange && (
          <RP.Item disabled>...</RP.Item>
        )}
        {(last - 2) === lastPageInRange && (
          <RP.Item onClick={() => setPage(lastPageInRange + 1)}>{lastPageInRange + 1}</RP.Item>
        )}
        {last > lastPageInRange && (
          <RP.Item onClick={() => setPage(last)}>{last}</RP.Item>
        )}

        <RP.Item disabled={current === last} onClick={() => setPage(current + 1)}>&raquo;</RP.Item>
      </RP>
    </div>
  )
}