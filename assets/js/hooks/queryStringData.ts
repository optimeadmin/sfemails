import { useSearchParams } from 'react-router-dom'

export function useGetQueryStringData () {
  const [searchParams] = useSearchParams({})

  return Object.fromEntries(searchParams.entries())
}

export function useQueryStringData () {
  const [searchParams, setQueryData] = useSearchParams({})

  return {
    queryData: Object.fromEntries(searchParams.entries()),
    setQueryData,
  }
}