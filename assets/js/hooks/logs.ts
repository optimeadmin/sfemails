import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { getLogs, resendEmails } from '../api/logs.ts'

export function useGetLogs () {
  const { isLoading, data } = useQuery({
    queryKey: ['logs'],
    queryFn: ({ signal }) => getLogs(signal),
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