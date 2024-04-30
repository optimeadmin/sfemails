import { useSearchParams } from 'react-router-dom'
import { parse } from 'qs'
import { useMemo } from 'react'

export function useGetQueryStringData () {
  const [searchParams] = useSearchParams({})
  const params = useMemo(() => parse(searchParams.toString()), [searchParams])

  return params
}

export function useQueryStringData () {
  const [searchParams, setQueryData] = useSearchParams({})
  const params = useMemo(() => parse(searchParams.toString()), [searchParams])

  return {
    queryData: params,
    setQueryData,
  }
}