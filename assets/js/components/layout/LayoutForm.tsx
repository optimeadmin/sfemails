import React from 'react'
import { FormErrors, FormLabel, FormRow } from '../ui/form/field'
import { Col, FormControl, FormText, Row } from 'react-bootstrap'
import { FieldWithLocale, TranslatableFields } from '../ui/form/TranslatableFields'
import { ActionsContainer } from '../ui/form/ActionsContainer'
import { Link, useNavigate } from 'react-router-dom'
import { FormProvider, useForm } from 'react-hook-form'
import { ExistentLayout, Layout } from '../../types'
import { useSaveLayout } from '../../hooks/layout'
import { ButtonWithLoading } from '../ui/ButtonWithLoading'
import { ControlledCodeMirror } from '../ui/CodeMirror'
import { toast } from 'react-toastify'
import { CloseModal } from '../ui/AppModal.tsx'

type LayoutFormProps = {
  layout?: ExistentLayout
  fromModal?: boolean,
  onSuccess?: (data: Layout) => void | Promise<void>,
}

export function LayoutForm ({ layout, fromModal = false, onSuccess }: LayoutFormProps) {
  const navigate = useNavigate()
  const form = useForm<Layout>({ values: layout })
  const { register } = form
  const isEdit = !!layout

  const { save, isPending } = useSaveLayout(form.setError)

  async function sendForm (data: Layout) {
    await save(data)
    if (!fromModal && !isEdit) {
      navigate('/layouts')
    }

    onSuccess?.(data)

    toast.success(isEdit ? 'Layout saved successfully!' : 'Layout created successfully!', {
      autoClose: isEdit ? 1000 : 3000
    })
  }

  return (
    <form onSubmit={form.handleSubmit(sendForm)}>
      <FormProvider {...form}>
        <FormRow className="mb-3" name="description">
          <FormLabel>Description</FormLabel>
          <FormControl as="textarea" {...register('description', { required: true })}/>
          <FormErrors/>
        </FormRow>

        <FormRow>
          <FormLabel>Content</FormLabel>
          <FormText className="mb-2 d-inline-block">
            Available variables: <br/>
            <span className="user-select-all fw-semibold">{'{{ _show_url }}'}</span>
            {' '}URL to view the email from the app. (Example:
            {' '}<span className="user-select-all">{'<a href="{{ _show_url }}">View in App</a>'})</span><br/>
            <span className="user-select-all fw-semibold">{'{{ _locale }}'}</span> Email content Language<br/>
            <span className="user-select-all fw-semibold">{'{{ __app__ }}'}</span> Email Application Title<br/>
          </FormText>
          <Row>
            <TranslatableFields
              render={({ locale }) => (
                <Col xs={12} xl={6}>
                  <FormRow name={`content.${locale}`}>
                    <FieldWithLocale locale={locale} className="mb-3">
                      <ControlledCodeMirror name={`content.${locale}`} rules={{ required: true }}/>
                    </FieldWithLocale>
                    <FormErrors/>
                  </FormRow>
                </Col>
              )}
            />
          </Row>
        </FormRow>
      </FormProvider>

      <ActionsContainer>
        {fromModal
          ? <CloseModal>Cancel</CloseModal>
          : <Link to="/layouts" className="btn btn-outline-secondary">Cancel</Link>
        }
        <ButtonWithLoading type="submit" isLoading={isPending}>{isEdit ? 'Save' : 'Create'}</ButtonWithLoading>
      </ActionsContainer>
    </form>
  )
}
