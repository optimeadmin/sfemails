import { keepPreviousData, useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { getLogs, resendEmails } from '../api/logs.ts'
import { useGetQueryStringData } from './queryStringData.ts'

export function useGetLogs () {
  const queryData = useGetQueryStringData()
  console.log({ queryData })

  const { isLoading, data } = useQuery({
    queryKey: ['logs', queryData],
    queryFn: ({ signal }) => getLogs(queryData, signal),
    placeholderData: keepPreviousData,
  })

  const paginationData = data?.paginationData || null
  const logs = data?.data || null

  return {
    isLoading,
    logs,
    paginationData,
  }
}

export function useResendEmail (uuids: string[]) {
  const queryClient = useQueryClient()
  const { mutateAsync, isPending } = useMutation({
    async mutationFn () {
      return await resendEmails(uuids)
    },
    onSuccess () {
      queryClient.invalidateQueries({ queryKey: ['logs'] })
    }
  })

  return {
    isPending,
    resend: mutateAsync,
  }
}