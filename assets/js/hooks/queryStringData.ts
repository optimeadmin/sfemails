import { useSearchParams } from 'react-router-dom'
import {parse} from 'qs'

export function useGetQueryStringData () {
  const [searchParams] = useSearchParams({})

  return parse(searchParams.toString())
}

export function useQueryStringData () {
  const [searchParams, setQueryData] = useSearchParams({})

  return {
    queryData: parse(searchParams.toString()),
    setQueryData,
  }
}