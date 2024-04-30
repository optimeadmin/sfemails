import { keepPreviousData, useIsFetching, useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { getLogs, resendEmails } from '../api/logs.ts'
import { useGetQueryStringData, useQueryStringData } from './queryStringData.ts'
import { useForm } from 'react-hook-form'
import { useEffect, useState } from 'react'

export function useGetLogs () {
  const queryData = useGetQueryStringData()

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

type Filters = {
  apps?: string[],
  configs?: string[],
  logId?: string,
  recipients?: string,
  sendAt?: string,
  statuses?: string[],
  subject?: string,
}

const emptyFilters: Filters = {
  apps: [],
  configs: [],
  logId: '',
  recipients: '',
  sendAt: '',
  statuses: [],
  subject: '',
}

export function useLogsFilter () {
  const isFetching = useIsFetching({ queryKey: ['logs'] }) > 0
  const { setQueryData, queryData } = useQueryStringData()
  const [defaultOpenFilters] = useState(() => !!queryData && Object.keys(queryData).length > 0)
  const [status, setStatus] = useState<null | 'searching' | 'clearing'>(null)

  const form = useForm<Filters>({
    defaultValues: async () => {
      const data = { ...emptyFilters, ...queryData }
      if (Array.isArray(data.recipients)) {
        data.recipients = data.recipients.join('\n')
      }

      return data
    }
  })

  function submit (data: Filters) {
    setStatus('searching')
    const mappedData = { ...data, recipients: data.recipients?.split('\n').filter(Boolean) ?? [] }
    setQueryData({ ...queryData, ...mappedData, page: '1' })
  }

  function clear () {
    setStatus('clearing')
    form.reset(emptyFilters)
    setQueryData({ ...queryData, ...emptyFilters, page: '1' })
  }

  useEffect(() => {
    if (!isFetching) {
      setStatus(null)
    }
  }, [isFetching])

  return {
    status: isFetching ? status : null,
    form,
    defaultOpenFilters,
    submit,
    clear,
  }
}